ALTER TABLE `vm_category` ADD `categoryImage` VARCHAR(255) NOT NULL AFTER `categoryName`;

ALTER TABLE `vm_course` ADD `isPopular` TINYINT(4) NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `vm_category`  ADD `isPopular` TINYINT(4) NOT NULL DEFAULT '0'  AFTER `status`;


CREATE TABLE `vm_user_recently_view_course` (
  `viewId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `addedOn` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `vm_user_recently_view_course` ADD PRIMARY KEY (`viewId`);
ALTER TABLE `vm_user_recently_view_course` MODIFY `viewId` int(11) NOT NULL AUTO_INCREMENT;

-- Course Subsctions 
CREATE TABLE `vm_course_subscription` (
  `courseSubscriptionId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `subscriptionPlanId` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `currency` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1 means USD, 2 means RUB',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `addedOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `vm_course_subscription` ADD PRIMARY KEY (`courseSubscriptionId`);
ALTER TABLE `vm_course_subscription` MODIFY `courseSubscriptionId` int(11) NOT NULL AUTO_INCREMENT;
