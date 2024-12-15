-- Prevent users from following themselves
CREATE FUNCTION prevent_self_follow()
RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF NEW.user1_id = NEW.user2_id THEN
        RAISE EXCEPTION 'A user cannot follow themselves';
    END IF;
    RETURN NEW;
END;
$BODY$ 
LANGUAGE plpgsql;

CREATE TRIGGER check_self_follow
    BEFORE INSERT ON follows
    FOR EACH ROW
    EXECUTE FUNCTION prevent_self_follow();


----------------------------------------
-- Anonymizar user data when a user is deleted
CREATE OR REPLACE FUNCTION anonymize_user_data()
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Anonymize user data in the 'users' table
    UPDATE users
    SET 
        username = CONCAT('deleted_user_', OLD.id),
        firstname = 'Anonymous',
        surname = 'User',
        bio_description = NULL,
        email = NULL,
        is_public = FALSE,
        profile_picture = NULL
    WHERE id = OLD.id;

    -- Remove user from any groups they were part of
    DELETE FROM group_participant
    WHERE user_id = OLD.id;

    RETURN NULL; 
END;
$BODY$ 
LANGUAGE plpgsql;

CREATE TRIGGER anonymize_user_on_delete
AFTER DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION anonymize_user_data();


----------------------------------------
--Prevent like more than one time a post
CREATE FUNCTION prevent_more_post_likes() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Check if the like already exists for the post by the same user
    IF EXISTS (
        SELECT 1 
        FROM post_like 
        WHERE post_id = NEW.post_id AND user_id = NEW.user_id
    ) THEN
        RAISE EXCEPTION 'A user can only like a post once';
    END IF;
    -- If no duplicate, allow the like insertion
    RETURN NEW;
END;
$BODY$ 
LANGUAGE plpgsql;

CREATE TRIGGER check_more_post_likes
    BEFORE INSERT ON post_like
    FOR EACH ROW
    EXECUTE FUNCTION prevent_more_post_likes();


----------------------------------------
-- Like post notification
CREATE FUNCTION notify_post_like() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Insert a notification for the post owner
    INSERT INTO notification (description, date, user_id)
    VALUES ('Your post received a like!', CURRENT_DATE, (SELECT user_id FROM post WHERE id = NEW.post_id));

    -- Get the notification ID of the created notification
    INSERT INTO post_notification (notification_id, trigger_post_id, post_notification_type)
    VALUES (currval('notification_id_seq'), NEW.post_id, 'post_likes'); 
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER post_like_notification
    AFTER INSERT ON post_like
    FOR EACH ROW
    EXECUTE FUNCTION notify_post_like();


----------------------------------------
-- Comment post notification
CREATE FUNCTION notify_post_comment() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Insert a notification for the post owner
    INSERT INTO notification (description, date, user_id)
    VALUES ('Your post received a new comment!', CURRENT_DATE, (SELECT user_id FROM post WHERE id = NEW.post_id));

    -- Insert post-specific notification
    INSERT INTO post_notification (notification_id, trigger_post_id, post_notification_type)
    VALUES (currval('notification_id_seq'), NEW.post_id, 'post_comments');
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER post_comment_notification
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE FUNCTION notify_post_comment();


----------------------------------------
-- Comment lile notification
CREATE FUNCTION notify_comment_like() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Insert a notification for the comment owner
    INSERT INTO notification (description, date, user_id)
    VALUES ('Your comment received a like!', CURRENT_DATE, (SELECT user_id FROM comment WHERE id = NEW.comment_id));

    -- Insert comment-specific notification
    INSERT INTO comment_notification (notification_id, trigger_comment_id, comment_notification_type)
    VALUES (currval('notification_id_seq'), NEW.comment_id, 'comment_likes');
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER comment_like_notification
    AFTER INSERT ON comment_like
    FOR EACH ROW
    EXECUTE FUNCTION notify_comment_like();


----------------------------------------
-- Tag in a post notification
CREATE FUNCTION notify_post_tag() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Insert a notification for the tagged user
    INSERT INTO notification (description, date, user_id)
    VALUES ('You were tagged in a post!', CURRENT_DATE, NEW.user_id);

    -- Insert post-specific notification
    INSERT INTO post_notification (notification_id, trigger_post_id, post_notification_type)
    VALUES (currval('notification_id_seq'), NEW.post_id, 'post_tags');
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER post_tag_notification
    AFTER INSERT ON post_tag
    FOR EACH ROW
    EXECUTE FUNCTION notify_post_tag();


----------------------------------------
-- Follow request notification
CREATE FUNCTION notify_follow_request() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Insert a notification for the user being followed
    INSERT INTO notification (description, date, user_id)
    VALUES ('You have a new follow request!', CURRENT_DATE, NEW.user2_id);

    -- Insert user-specific notification
    INSERT INTO user_notification (notification_id, trigger_user_id, user_notification_type)
    VALUES (currval('notification_id_seq'), NEW.user1_id, 'follow_request');
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER follow_request_notification
    AFTER INSERT ON follows
    FOR EACH ROW
    EXECUTE FUNCTION notify_follow_request();


----------------------------------------
-- Group join request notification
CREATE FUNCTION notify_group_join_request()
RETURNS TRIGGER AS
$BODY$
BEGIN
    -- Check if the group is public
    IF NOT (SELECT is_public FROM groups WHERE id = NEW.group_id) THEN
        -- Insert a notification for the group owner
        INSERT INTO notification (description, date, user_id)
        VALUES ('A user has requested to join your group.', CURRENT_DATE, (SELECT owner_id FROM groups WHERE id = NEW.group_id));

        -- Insert group-owner-specific notification
        INSERT INTO group_owner_notification (notification_id, trigger_group_id, group_owner_notification_type)
        VALUES (currval('notification_id_seq'), NEW.group_id, 'join_request');
    ELSE
        -- Insert a notification for the user who requested to join the group
        INSERT INTO notification (description, date, user_id)
        VALUES ('A user has join the group!', CURRENT_DATE, (SELECT owner_id FROM groups WHERE id = NEW.group_id));

        -- Insert user-specific notification
        INSERT INTO group_owner_notification (notification_id, trigger_group_id, group_owner_notification_type)
        VALUES (currval('notification_id_seq'), NEW.group_id, 'new_user_join');
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER group_join_request_notification
    AFTER INSERT ON group_participant
    FOR EACH ROW
    EXECUTE FUNCTION notify_group_join_request();


----------------------------------------
-- New message notification
CREATE OR REPLACE FUNCTION notify_group_message() 
RETURNS TRIGGER AS 
$BODY$
DECLARE
    notif_id BIGINT; -- Variable to store the generated notification_id
BEGIN
    -- Insert a notification for each group member except the sender
    FOR notif_id IN
        INSERT INTO notification (description, date, user_id)
        SELECT 'New message in your group.', CURRENT_DATE, user_id
        FROM group_participant
        WHERE group_id = NEW.group_id AND user_id != NEW.sender_id
        RETURNING id -- Return the notification_id for each inserted row
    LOOP
        -- Insert group-member-specific notification
        INSERT INTO group_member_notification (notification_id, trigger_group_id, group_member_notification_type)
        VALUES (notif_id, NEW.group_id, 'new_message');
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER group_message_notification
    AFTER INSERT ON message
    FOR EACH ROW
    EXECUTE FUNCTION notify_group_message();


----------------------------------------
-- Answer in your comment notification
CREATE FUNCTION notify_comment_reply() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Insert a notification for the comment owner of the previous comment
    INSERT INTO notification (description, date, user_id)
    VALUES ('Your comment received a reply!', CURRENT_DATE, (SELECT user_id FROM comment WHERE id = NEW.previous_comment_id));

    -- Insert comment-specific notification
    INSERT INTO comment_notification (notification_id, trigger_comment_id, comment_notification_type)
    VALUES (currval('notification_id_seq'), NEW.id, 'comment_answer');
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER comment_reply_notification
    AFTER INSERT ON comment
    FOR EACH ROW
    WHEN (NEW.previous_comment_id IS NOT NULL)
    EXECUTE FUNCTION notify_comment_reply();


----------------------------------------
-- Follow request accepted notification
CREATE FUNCTION notify_follow_accepted() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Insert a notification for the user who sent the follow request
    INSERT INTO notification (description, date, user_id)
    VALUES ('Your follow request has been accepted!', CURRENT_DATE, NEW.user1_id);

    -- Insert user-specific notification
    INSERT INTO user_notification (notification_id, trigger_user_id, user_notification_type)
    VALUES (currval('notification_id_seq'), NEW.user2_id, 'follow_response');
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER follow_accepted_notification
    AFTER UPDATE ON follows
    FOR EACH ROW
    WHEN (OLD.user2_id IS DISTINCT FROM NEW.user2_id)
    EXECUTE FUNCTION notify_follow_accepted();


