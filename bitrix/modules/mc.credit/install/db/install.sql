INSERT INTO b_sale_status (ID, TYPE, SORT, NOTIFY) VALUES ('A', 'O', 50, 'Y');
INSERT INTO b_sale_status (ID, TYPE, SORT, NOTIFY) VALUES ('R', 'O', 30, 'Y');

INSERT INTO b_sale_status_lang (STATUS_ID, LID, NAME, DESCRIPTION) VALUES ('A', 'ru', 'Анкета заполнена', 'Анкета на кредит заполнена');
INSERT INTO b_sale_status_lang (STATUS_ID, LID, NAME, DESCRIPTION) VALUES ('R', 'ru', 'Анкета отклонена', 'Анкета на кредит отклонена');

INSERT INTO b_sale_status_group_task (STATUS_ID, GROUP_ID, TASK_ID) VALUES ('A', 7, 75);
INSERT INTO b_sale_status_group_task (STATUS_ID, GROUP_ID, TASK_ID) VALUES ('R', 7, 75);
