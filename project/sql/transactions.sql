SET search_path TO lbaw24121;

--------------------------TRANSACTIONS------------------------------

--| TRAN01 - Create a new group and add the creator as a participant
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

INSERT INTO groups (name, description, is_public, img_id, owner_id)
VALUES ($name, $description, $is_public, $img_id, $owner_id)

INSERT INTO group_participant (user_id, group_id)
VALUES ($owner_id, CURRVAL('groups_id_seq'));

END TRANSACTION;

--| TRAN02 - Create a new post with initial tags

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

INSERT INTO post (creation_date, description, user_id, post_picture_id)
    VALUES (CURRENT_DATE, $description, $user_id, $picture_id);

IF $tag_user_id IS NOT NULL THEN
    INSERT INTO post_tag (post_id, user_id)
        VALUES (CURRVAL('post_id_seq'), $tag_user_id);
    INSERT INTO notification(id, description, date, user_id)
        VALUES (NEXTVAL('notification_id_seq'), 'Post tag', CURRENT_DATE, $tag_user_id)
    INSERT INTO post_notification (notification_id, trigger_post_id, post_notification_type)
        VALUES (CURRVAL('notification_id_seq'), CURRVAL('post_id_seq'), 'post_tags');

END IF;

END TRANSACTION;

--| TRAN03 - Comment on a post

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

INSERT INTO comment (content, date, post_id, user_id)
    VALUES ($content, CURRENT_DATE, $post_id, $user_id);
INSERT INTO notification(id, description, date, user_id)
    VALUES (NEXTVAL('notification_id_seq'), 'New Comment on Post', CURRENT_DATE, (SELECT user_id FROM post WHERE id = $post_id));
INSERT INTO post_notification (notification_id, trigger_post_id, post_notification_type)
    VALUES (CURRVAL('notification_id_seq'), $post_id, 'post_comments');

END TRANSACTION;

--| TRAN04 - Delete Account

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

DELETE FROM group_participant WHERE user_id = $user_id;
DELETE FROM follows WHERE user1_id = $user_id OR user2_id = $user_id;
DELETE FROM user_notification WHERE trigger_user_id = $user_id;
DELETE FROM post_notification WHERE notification_id IN
    (SELECT id FROM notification WHERE user_id = $user_id);
DELETE FROM comment_notification WHERE notification_id IN
    (SELECT id FROM notification WHERE user_id = $user_id);
DELETE FROM group_owner_notification WHERE notification_id IN
    (SELECT id FROM notification WHERE user_id = $user_id);
DELETE FROM group_member_notification WHERE notification_id IN
    (SELECT id FROM notification WHERE user_id = $user_id);
DELETE FROM post_like WHERE user_id = $user_id;
DELETE FROM comment_like WHERE user_id = $user_id;
DELETE FROM post_tag WHERE user_id = $user_id;
DELETE FROM comment_tag WHERE user_id = $user_id;
DELETE FROM message WHERE sender_id = $user_id;
DELETE FROM appeal WHERE ban_id IN (SELECT id FROM ban WHERE user_id = $user_id);
DELETE FROM ban WHERE user_id = $user_id;
DELETE FROM notification WHERE user_id = $user_id;
DELETE FROM comment WHERE user_id = $user_id;
DELETE FROM post WHERE user_id = $user_id;
DELETE FROM users WHERE id = $user_id;

END TRANSACTION;

--| TRAN05 - Leave a group

BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
DELETE FROM group_participant WHERE user_id = $user_id AND group_id = $group_id;

INSERT INTO notification(id, description, date, user_id)
    VALUES (NEXTVAL('notification_id_seq'), 'Member left the group', CURRENT_DATE, (SELECT owner_id FROM groups WHERE id = $group_id));
INSERT INTO group_owner_notification (notification_id, trigger_group_id, group_owner_notification_type)
    VALUES (CURRVAL('notification_id_seq'), $group_id, 'member_leave');

END TRANSACTION;

--| TRAN06 - Allows a user to like a post, preventing duplicate likes, and notifies the post owner about the new like.

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

IF (SELECT COUNT(*) FROM post_like WHERE post_id = $post_id AND user_id = $user_id) = 0 THEN
    INSERT INTO post_like (post_id, user_id)
        VALUES ($post_id, $user_id);
    INSERT INTO notification(id, description, date, user_id)
        VALUES (NEXTVAL('notification_id_seq'), 'New Like on Post', CURRENT_DATE, (SELECT user_id FROM post WHERE id = $post_id));
    INSERT INTO post_notification (notification_id, trigger_post_id, post_notification_type)
        VALUES (CURRVAL('notification_id_seq'), $post_id, 'post_likes');

ELSE
    RAISE EXCEPTION 'You have already liked this post';
END IF;

END TRANSACTION;

--| TRAN07 - Edit Profile

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

UPDATE users

SET username = $new_username,
    firstname = $new_firstname,
    surname = $new_surname,
    email = $new_email,    
    bio_description = $new_bio_description
    WHERE id = $user_id;

UPDATE users
SET is_public = $new_is_public
    WHERE id = $user_id;

IF $new_profile_picture IS NOT NULL THEN

UPDATE users
SET profile_picture = $new_profile_picture
    WHERE id = $user_id;
END IF;

END TRANSACTION;

--| TRAN08 - React to a comment

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

IF (SELECT COUNT(*) FROM comment_like WHERE comment_id = $comment_id AND user_id = $user_id) = 0 THEN

INSERT INTO comment_like (comment_id, user_id)
    VALUES ($comment_id, $user_id);
INSERT INTO notification(id, description, date, user_id)
    VALUES (NEXTVAL('notification_id_seq'), 'New Like on Comment', CURRENT_DATE, (SELECT user_id FROM comment WHERE id = $comment_id));
INSERT INTO comment_notification (notification_id, trigger_comment_id, comment_notification_type, user_id)
    VALUES (CURRVAL('notification_id_seq'), $comment_id, 'comment_likes');
ELSE
    RAISE EXCEPTION 'You have already liked this comment';
END IF;

END TRANSACTION;

--| TRAN09 - Remove Member from Group

BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

INSERT INTO notification(id, description, date, user_id)
    VALUES (NEXTVAL('notification_id_seq'), 'Removed from group', CURRENT_DATE, (SELECT user_id FROM group_participant WHERE group_id = $group_id));
INSERT INTO group_member_notification (notification_id, trigger_group_id, group_member_notification_type)
    VALUES (CURRVAL('notification_id_seq'), $group_id, 'member_removed');
DELETE FROM group_participant WHERE user_id = $member_id AND group_id = $group_id;

END TRANSACTION;

--| TRAN10 - Add Member to Group

BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

INSERT INTO group_participant (user_id, group_id)
    VALUES ($user_id, $group_id);
INSERT INTO notification (id, description, date, user_id)
    VALUES (NEXTVAL('notification_id_seq'), 'Joined group', CURRENT_DATE, (SELECT user_id FROM group_participant WHERE group_id = $group_id));
INSERT INTO group_member_notification (notification_id, trigger_group_id, response_type, group_member_notification_type)
    VALUES (CURRVAL('notification_id_seq'), $group_id, 'accepted', 'join_response');

END TRANSACTION;
