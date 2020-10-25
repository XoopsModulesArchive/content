#
# Table structure for table `content`
#
#Create Table

CREATE TABLE content (
    `storyid`      INT(8)          NOT NULL AUTO_INCREMENT,
    `parent_id`    INT(8)          NOT NULL DEFAULT '0',
    `blockid`      INT(8) UNSIGNED NOT NULL DEFAULT '0',
    `title`        VARCHAR(255)    NOT NULL DEFAULT '',
    `ptitle`       VARCHAR(255)             DEFAULT NULL,
    `text`         LONGTEXT,
    `visible`      TINYINT(1)      NOT NULL DEFAULT '0',
    `homepage`     TINYINT(1)      NOT NULL DEFAULT '0',
    `epage`        TINYINT(1)               DEFAULT '0',
    `nohtml`       TINYINT(1)      NOT NULL DEFAULT '0',
    `nosmiley`     TINYINT(1)      NOT NULL DEFAULT '0',
    `nobreaks`     TINYINT(1)      NOT NULL DEFAULT '0',
    `nocomments`   TINYINT(1)      NOT NULL DEFAULT '0',
    `link`         TINYINT(1)      NOT NULL DEFAULT '0',
    `address`      VARCHAR(255)             DEFAULT NULL,
    `submenu`      TINYINT(1)      NOT NULL DEFAULT '0',
    `newwindow`    TINYINT(1)      NOT NULL DEFAULT '0',
    `date`         DATETIME        NOT NULL DEFAULT '0000-00-00 00:00:00',
    `assoc_module` INT(8) UNSIGNED          DEFAULT NULL,
    `header_img`   VARCHAR(255)             DEFAULT NULL,
    PRIMARY KEY (`storyid`),
    KEY `title` (`title`(40))
)
    ENGINE = ISAM;
