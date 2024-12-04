
-----------------------------------------
--
-- Use this code to drop and create a schema.
-- In this case, the DROP TABLE statements can be removed.
--
-- DROP SCHEMA lbaw2425 CASCADE;
-- CREATE SCHEMA lba2425;
-- SET search_path TO lbaw2425
-----------------------------------------
CREATE SCHEMA if NOT EXISTS lbaw24121;
SET search_path TO lbaw24121;

SET DateStyle TO European;

-----------------------------------------
-- Drop old schema
-----------------------------------------



DROP TABLE IF EXISTS group_participant;
DROP TABLE IF EXISTS group_member_notification;
DROP TABLE IF EXISTS group_owner_notification;
DROP TABLE IF EXISTS post_notification;
DROP TABLE IF EXISTS comment_notification;
DROP TABLE IF EXISTS user_notification;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS follows;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS appeal;
DROP TABLE IF EXISTS ban;
DROP TABLE IF EXISTS comment_tag;
DROP TABLE IF EXISTS post_tag;
DROP TABLE IF EXISTS comment_like;
DROP TABLE IF EXISTS post_like;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS picture;

DROP TYPE IF EXISTS profile_type;
DROP TYPE IF EXISTS response_type;
DROP TYPE IF EXISTS user_notification_type;
DROP TYPE IF EXISTS post_notification_type;
DROP TYPE IF EXISTS comment_notification_type;
DROP TYPE IF EXISTS group_owner_notification_type;
DROP TYPE IF EXISTS group_member_notification_type;

-----------------------------
--------TYPES---------------
-----------------------------

CREATE TYPE profile_type AS ENUM ('pet owner', 'admin', 'veterinarian', 'adoption organization', 'rescue organization','deleted');
CREATE TYPE response_type AS ENUM ('accepted', 'rejected', 'pending');
CREATE TYPE user_notification_type AS ENUM('follow_request', 'follow_response', 'start_following');
CREATE TYPE post_notification_type AS ENUM('post_likes', 'post_comments', 'post_tags');
CREATE TYPE comment_notification_type AS ENUM('comment_answer', 'comment_likes', 'comment_tags');
CREATE TYPE group_owner_notification_type AS ENUM('join_request', 'member_leave');
CREATE TYPE group_member_notification_type AS ENUM('join_response', 'new_message', 'group_removed');

-----------------------------
--------CREATE---------------
-----------------------------

CREATE TABLE picture (
    id SERIAL PRIMARY KEY,
    img_path TEXT NOT NULL
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE,
    firstname TEXT,
    surname TEXT,
    password TEXT,
    email TEXT ,
    bio_description TEXT,
    is_public BOOLEAN NOT NULL DEFAULT TRUE,
    admin BOOLEAN NOT NULL DEFAULT FALSE,
    type profile_type NOT NULL,
    profile_picture TEXT NOT NULL DEFAULT 'default.png'
);

CREATE TABLE groups (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    is_public BOOLEAN NOT NULL DEFAULT FALSE,
    owner_id INT NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE group_participant (
    user_id INT NOT NULL,
    group_id INT NOT NULL,
    PRIMARY KEY (user_id, group_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    creation_date DATE NOT NULL DEFAULT CURRENT_DATE,
    description TEXT,
    user_id INT NOT NULL,
    post_picture TEXT,
    is_public BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    previous_comment_id INT,
    FOREIGN KEY (post_id) REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (previous_comment_id) REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE post_like (
    id SERIAL PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE comment_like (
    id SERIAL PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (comment_id) REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE post_tag (
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (post_id, user_id),
    FOREIGN KEY (post_id) REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE comment_tag(
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY(comment_id, user_id),
    FOREIGN KEY (comment_id) REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE message (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    sender_id INT NOT NULL,
    group_id INT NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE ban (
    id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE appeal (
    id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    status BOOLEAN NOT NULL DEFAULT FALSE,
    ban_id INT NOT NULL,
    FOREIGN KEY (ban_id) REFERENCES ban(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE user_notification (
    notification_id INT PRIMARY KEY,
    trigger_user_id INT NOT NULL,
    response_type response_type,
    user_notification_type user_notification_type NOT NULL,
    FOREIGN KEY (notification_id) REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (trigger_user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE post_notification (
    notification_id INT PRIMARY KEY,
    trigger_post_id INT NOT NULL,
    post_notification_type post_notification_type NOT NULL,
    FOREIGN KEY (notification_id) REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (trigger_post_id) REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE comment_notification (
    notification_id INT PRIMARY KEY,
    trigger_comment_id INT NOT NULL,
    comment_notification_type comment_notification_type NOT NULL,
    FOREIGN KEY (notification_id) REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (trigger_comment_id) REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE group_owner_notification (
    notification_id INT PRIMARY KEY,
    trigger_group_id INT NOT NULL,
    group_owner_notification_type group_owner_notification_type NOT NULL,
    FOREIGN KEY (notification_id) REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (trigger_group_id) REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE group_member_notification (
    notification_id INT PRIMARY KEY,
    trigger_group_id INT NOT NULL,
    response_type response_type,
    group_member_notification_type group_member_notification_type,
    FOREIGN KEY (notification_id) REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (trigger_group_id) REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE follows (
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    request_status response_type NOT NULL,
    PRIMARY KEY (user1_id, user2_id),
    FOREIGN KEY (user1_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);




