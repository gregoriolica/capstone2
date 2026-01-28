TYPE=VIEW
query=select `d`.`doctor_id` AS `doctor_id`,`u`.`first_name` AS `first_name`,`u`.`last_name` AS `last_name`,`d`.`specialization` AS `specialization`,`d`.`schedule` AS `schedule`,`s`.`service_name` AS `service_name` from (((`jam_db`.`doctor` `d` join `jam_db`.`users` `u` on(`d`.`user_id` = `u`.`id`)) join `jam_db`.`doctor_services` `ds` on(`d`.`doctor_id` = `ds`.`doctor_id`)) join `jam_db`.`service_catalog` `s` on(`ds`.`service_type_id` = `s`.`service_type_id`))
md5=48b3de1520f883dca6fd24c8cb810e99
updatable=1
algorithm=0
definer_user=root
definer_host=localhost
suid=1
with_check_option=0
timestamp=0001763085977631004
create-version=2
source=SELECT `d`.`doctor_id` AS `doctor_id`, `u`.`first_name` AS `first_name`, `u`.`last_name` AS `last_name`, `d`.`specialization` AS `specialization`, `d`.`schedule` AS `schedule`, `s`.`service_name` AS `service_name` FROM (((`doctor` `d` join `users` `u` on(`d`.`user_id` = `u`.`id`)) join `doctor_services` `ds` on(`d`.`doctor_id` = `ds`.`doctor_id`)) join `service_catalog` `s` on(`ds`.`service_type_id` = `s`.`service_type_id`))
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `d`.`doctor_id` AS `doctor_id`,`u`.`first_name` AS `first_name`,`u`.`last_name` AS `last_name`,`d`.`specialization` AS `specialization`,`d`.`schedule` AS `schedule`,`s`.`service_name` AS `service_name` from (((`jam_db`.`doctor` `d` join `jam_db`.`users` `u` on(`d`.`user_id` = `u`.`id`)) join `jam_db`.`doctor_services` `ds` on(`d`.`doctor_id` = `ds`.`doctor_id`)) join `jam_db`.`service_catalog` `s` on(`ds`.`service_type_id` = `s`.`service_type_id`))
mariadb-version=100432
