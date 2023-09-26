<?php

namespace Yandex\Market\Ui\Trading;

use Bitrix\Main;
use Yandex\Market;
use Yandex\Market\Reference\Assert;
use Yandex\Market\Trading\Service as TradingService;
use Yandex\Market\Trading\Setup as TradingSetup;

class OrderActivity extends Market\Ui\Reference\Page
{
	protected function getReadRights()
	{
		return Market\Ui\Access::RIGHTS_PROCESS_TRADING;
	}

	protected function getWriteRights()
	{
		return Market\Ui\Access::RIGHTS_PROCESS_TRADING;
	}

	public function show()
	{
		$setup = $this->getSetup();
		$type = $this->getType();
		list($path, $chain) = $this->splitType($type);
		$action = $this->getAction($setup, $path);
		$activity = $this->getActionActivity($action);
		$activity = $this->resolveActivity($activity, $chain);

		if ($activity instanceof TradingService\Reference\Action\FormActivity)
		{
			$this->showForm($setup, $path, $activity);
		}
		else if ($activity instanceof TradingService\Reference\Action\CommandActivity)
		{
			$commandResult = $this->executeCommand($setup, $path, $activity);

			$this->sendCommandResponse($commandResult);
		}
	}

	protected function showForm(
		TradingSetup\Model $setup,
		$path,
		TradingService\Reference\Action\FormActivity $activity
	)
	{
		global $APPLICATION;

		$APPLICATION->IncludeComponent('yandex.market:admin.form.edit', '', [
			'FORM_ID' => $this->getFormId(),
			'PROVIDER_TYPE' => 'TradingActivity',
			'PRIMARY' => $this->getOrderId(),
			'FIELDS' => $activity->getFields(),
			'ALLOW_SAVE' => $this->isAuthorized($this->getWriteRights()),
			'LAYOUT' => 'raw',
			'TRADING_SETUP' => $setup,
			'TRADING_ACTIVITY' => $activity,
			'TRADING_PATH' => $path,
		]);
	}

	protected function executeCommand(
		TradingSetup\Model $setup,
		$path,
		TradingService\Reference\Action\CommandActivity $activity
	)
	{
		$result = new Main\Result();
		$procedure = null;

		try
		{
			$this->checkCommandRequest();
			$this->checkSessid();

			$primary = $this->getOrderId();
			$tradingInfo = $this->getTradingInfo($setup, $primary);
			$payload = $activity->getPayload() + $this->getTradingPayload($tradingInfo);

			$procedure = new Market\Trading\Procedure\Runner(
				Market\Trading\Entity\Registry::ENTITY_TYPE_ORDER,
				$tradingInfo['ACCOUNT_NUMBER']
			);

			$procedure->run($setup, $path, $payload);
		}
		catch (Market\Exceptions\Trading\NotImplementedAction $exception)
		{
			$result->addError(new Main\Error($exception->getMessage()));
		}
		catch (Market\Exceptions\Api\Request $exception)
		{
			$result->addError(new Main\Error($exception->getMessage()));
		}
		catch (\Exception $exception)
		{
			if ($procedure !== null)
			{
				$procedure->logException($exception);
			}

			$result->addError(new Main\Error($exception->getMessage()));
		}

		return $result;
	}

	protected function checkCommandRequest()
	{
		if ($this->request->getPost('command') !== 'Y')
		{
			throw new Main\ArgumentException('missing command request');
		}
	}

	protected function checkSessid()
	{
		if (!check_bitrix_sessid())
		{
			throw new Main\ArgumentException('session expired');
		}
	}

	protected function getTradingInfo(TradingSetup\Model $setup, $primary)
	{
		$platform = $setup->getPlatform();
		$orderRegistry = $setup->getEnvironment()->getOrderRegistry();

		return [
			'INTERNAL_ORDER_ID' => $orderRegistry->search($primary, $platform, false),
			'EXTERNAL_ORDER_ID' => $primary,
			'ACCOUNT_NUMBER' => $orderRegistry->search($primary, $platform),
		];
	}

	protected function getTradingPayload(array $tradingInfo)
	{
		return [
			'internalId' => $tradingInfo['INTERNAL_ORDER_ID'],
			'orderId' => $tradingInfo['EXTERNAL_ORDER_ID'],
			'orderNum' => $tradingInfo['ACCOUNT_NUMBER'],
			'immediate' => true,
		];
	}

	protected function sendCommandResponse(Main\Result $commandResult)
	{
		global $APPLICATION;

		$response = [
			'status' => $commandResult->isSuccess() ? 'ok' : 'error',
			'message' => !$commandResult->isSuccess() ? implode(PHP_EOL, $commandResult->getErrorMessages()) : '',
		];

		$APPLICATION->RestartBuffer();
		echo Main\Web\Json::encode($response);
		die();
	}

	/** @return TradingSetup\Model */
	protected function getSetup()
	{
		$id = $this->getSetupId();

		return Market\Trading\Setup\Model::loadById($id);
	}

	protected function getSetupId()
	{
		$result = (int)$this->request->get('setup');
		Assert::positiveInteger($result, 'setup');

		return $result;
	}

	protected function getAction(TradingSetup\Model $setup, $path)
	{
		$environment = $setup->getEnvironment();

		return $setup->getService()->getRouter()->getDataAction($path, $environment);
	}

	protected function getType()
	{
		$result = $this->request->get('type');
		Assert::notNull($result, 'type');

		return (string)$result;
	}

	protected function splitType($type)
	{
		return explode('|', $type, 2);
	}

	protected function getActionActivity(TradingService\Reference\Action\DataAction $action)
	{
		if (!($action instanceof TradingService\Reference\Action\HasActivity))
		{
			throw new Main\NotSupportedException('action not supported activity');
		}

		return $action->getActivity();
	}

	protected function resolveActivity(TradingService\Reference\Action\AbstractActivity $activity, $chain = '')
	{
		if ($activity instanceof TradingService\Reference\Action\ComplexActivity)
		{
			list($selfChain, $childChain) = explode('.', $chain, 2);
			$map = $activity->getActivities();

			if (!isset($map[$selfChain]))
			{
				throw new Main\ArgumentException(sprintf('cant find %s complex activity', $selfChain));
			}

			$result = $this->resolveActivity($map[$selfChain], $childChain);
		}
		else
		{
			$result = $activity;
		}

		return $result;
	}

	protected function getFormId()
	{
		$type = $this->getType();
		$type = str_replace('/', '_', $type);
		$type = Market\Data\TextString::toUpper($type);

		return 'YANDEX_MARKET_ADMIN_ORDER_ACTIVITY_' . $type;
	}

	protected function getOrderId()
	{
		$result = $this->request->get('id');
		Assert::notNull($result, 'id');

		return (string)$result;
	}
}