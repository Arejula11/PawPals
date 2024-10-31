CREATE INDEX index_post ON post USING btree (creation_date,user_id);
CLUSTER post USING index_post;

CREATE INDEX index_comment ON comment USING btree (post_id,date);
CLUSTER comment USING index_comment;

CREATE INDEX index_notification ON notification USING btree (user_id,date);
CLUSTER notification USING index_notification;

