ALTER TABLE `playtasking_promo_tenant`.`award_user`
ADD INDEX `idx_award_user_full` (`model_id`, `model_type`, `user_id`, `hit`);


ALTER TABLE `playtasking_entraalmasalla_tenant`.`award_user`
ADD INDEX `idx_award_user_full` (`model_id`, `model_type`, `user_id`, `hit`);


ALTER TABLE `playtasking_exafm_tenant`.`award_user`
ADD INDEX `idx_award_user_full` (`model_id`, `model_type`, `user_id`, `hit`);

ALTER TABLE `playtasking_sabritas_tenant`.`award_user`
ADD INDEX `idx_award_user_full` (`model_id`, `model_type`, `user_id`, `hit`);


ALTER TABLE `playtasking_cantabria_tenant`.`award_user`
ADD INDEX `idx_award_user_full` (`model_id`, `model_type`, `user_id`, `hit`);

ALTER TABLE `playtasking_aeromexico_tenant`.`award_user`
ADD INDEX `idx_award_user_full` (`model_id`, `model_type`, `user_id`, `hit`);
