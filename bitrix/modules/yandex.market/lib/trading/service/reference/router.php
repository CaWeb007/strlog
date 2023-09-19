<?php

namespace Yandex\Market\Trading\Service\Reference;

use Bitrix\Main;
use Yandex\Market;
use Yandex\Market\Trading\Entity as TradingEntity;

abstract class Router
{
	protected $provider;
	protected $map;

	public function __construct(Provider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * �������������� �� �������� ��� ��������� ��������
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function hasHttpAction($path)
	{
		try
		{
			$className = $this->getActionClassName($path);

			$result = is_subclass_of($className, Action\HttpAction::class);
		}
		catch (Market\Exceptions\Trading\NotImplementedAction $exception)
		{
			$result = false;
		}

		return $result;
	}

	/**
	 * �������� ��� ��������� ��������
	 *
	 * @param string $path
	 * @param TradingEntity\Reference\Environment $environment
	 * @param Main\HttpRequest $request
	 * @param Main\Server $server
	 *
	 * @return mixed
	 * @throws Main\SystemException
	 * @throws Market\Exceptions\Trading\NotImplementedAction
	 */
	public function getHttpAction($path, TradingEntity\Reference\Environment $environment, Main\HttpRequest $request, Main\Server $server)
	{
		$className = $this->getActionClassName($path);

		if (!is_subclass_of($className, Action\HttpAction::class))
		{
			if (is_subclass_of($className, Action\DataAction::class))
			{
				throw new Market\Exceptions\Api\InvalidOperation('Action not supported for ' . $path);
			}
			else
			{
				throw new Main\SystemException($className . ' must extends ' . Action\HttpAction::class . ' for path ' . $path);
			}
		}

		return new $className($this->provider, $environment, $request, $server);
	}

	/**
	 * �������������� �� �������� ��� ��������� ������
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function hasDataAction($path)
	{
		try
		{
			$className = $this->getActionClassName($path);

			$result = is_subclass_of($className, Action\DataAction::class);
		}
		catch (Market\Exceptions\Trading\NotImplementedAction $exception)
		{
			$result = false;
		}

		return $result;
	}

	/**
	 * �������� ��� ��������� ������
	 *
	 * @param string $path
	 * @param TradingEntity\Reference\Environment $environment
	 * @param array $data
	 *
	 * @return Action\DataAction
	 * @throws Main\SystemException
	 * @throws Market\Exceptions\Trading\NotImplementedAction
	 */
	public function getDataAction($path, TradingEntity\Reference\Environment $environment, $data = [])
	{
		$className = $this->getActionClassName($path);

		if (!is_subclass_of($className, Action\DataAction::class))
		{
			if (is_subclass_of($className, Action\HttpAction::class))
			{
				throw new Market\Exceptions\Api\InvalidOperation('Action not supported for ' . $path);
			}
			else
			{
				throw new Main\SystemException($className . ' must extends ' . Action\DataAction::class . ' for path ' . $path);
			}
		}

		return new $className($this->provider, $environment, $data);
	}

	/**
	 * ����� �������� ��� ��������� �������
	 *
	 * @param string $path
	 *
	 * @return Action\AbstractAction
	 *
	 * @throws Main\SystemException
	 * @throws Market\Exceptions\Trading\NotImplementedAction
	 */
	protected function getActionClassName($path)
	{
		$map = $this->getMap();

		if (!isset($map[$path]))
		{
			$message = 'Action not found for ' . $path;
			throw new Market\Exceptions\Trading\NotImplementedAction($message);
		}

		return $map[$path];
	}

	/**
	 * �������������� �� ��������
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function hasAction($path)
	{
		$map = $this->getMap();

		return isset($map[$path]);
	}

	/**
	 * ������������ ����� � ��������
	 *
	 * @return array<string, Action\AbstractAction>
	 */
	public function getMap()
	{
		if ($this->map === null)
		{
			$this->map = $this->getUserMap() + $this->getSystemMap(); // allow user override actions
		}

		return $this->map;
	}

	/**
	 * @return array<string, Action\AbstractAction>
	 */
	abstract protected function getSystemMap();

	/**
	 * @return array<string, Action\AbstractAction>
	 */
	protected function getUserMap()
	{
		$result = [];
		$module = Market\Config::getModuleName();
		$name = 'onTradingActionBuildList';
		$parameters =[
			'PROVIDER' => $this->provider,
		];

		$event = new Main\Event($module, $name, $parameters);
		$event->send();

		foreach ($event->getResults() as $eventResult)
		{
			if ($eventResult->getType() !== Main\EventResult::SUCCESS) { continue; }

			$eventResultParameters = $eventResult->getParameters();

			if (!is_array($eventResultParameters))
			{
				throw new Main\SystemException('Event result parameters of ' . $name . ' must be an array');
			}

			foreach ($eventResultParameters as $path => $className)
			{
				if (!is_subclass_of($className, Action\AbstractAction::class))
				{
					throw new Main\SystemException($className . ' must extends ' . Action\AbstractAction::class . ' for path ' . $path);
				}

				$result[$path] = $className;
			}
		}

		return $result;
	}
}