-- OrgChart module database schema for FrontAccounting

ALTER TABLE IF EXISTS `fa_org_positions` RENAME TO `0_ksf_orgchart_positions`;

CREATE TABLE IF NOT EXISTS `0_ksf_orgchart_positions` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `parent_id` INT(11) DEFAULT NULL,
    `level` INT(2) NOT NULL DEFAULT 0,
    `employee_id` INT(11) DEFAULT NULL,
    `status` ENUM('Vacant','Filled') NOT NULL DEFAULT 'Vacant',
    PRIMARY KEY (`id`),
    KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `0_ksf_modules` (`name`, `version`, `enabled`, `installed`) VALUES ('OrgChart', '1.0.0', 1, NOW()) ON DUPLICATE KEY UPDATE `version` = '1.0.0';