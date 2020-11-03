CREATE TABLE IF NOT EXISTS `Products`
(
    `id`         int auto_increment,
    `name`        varchar(60) NOT NULL unique,
    `quantity`    int            default 0,
    `price`       decimal(10, 2) default 0.00,
    `description` TEXT,
    `modified`    TIMESTAMP       default current_timestamp on update current_timestamp,
    `created`     TIMESTAMP      default current_timestamp,
    `user_id`     int,
    PRIMARY KEY ('id'),
    FOREIGN KEY (`user_id`) REFERENCES Users (`id`),

)
