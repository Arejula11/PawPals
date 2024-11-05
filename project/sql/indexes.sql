--------------------------------------------
-- CREATE AND CLUSTER PERFORMANCE INDEXES --
--------------------------------------------
---- IDX01
CREATE INDEX index_post ON post USING btree (creation_date,user_id);
CLUSTER post USING index_post;

---- IDX02
CREATE INDEX index_comment ON comment USING btree (post_id,date);
CLUSTER comment USING index_comment;

---- IDX03
CREATE INDEX index_notification ON notification USING btree (user_id,date);
CLUSTER notification USING index_notification;

--------------------------------------------
-- CREATE FTS INDEXES ----------------------
--------------------------------------------

---- IDX04
-- Add column to user to store computed ts_vectors.
ALTER TABLE users
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
    -- Set weights and create the tsvector for new records
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors := (
            setweight(to_tsvector('english', COALESCE(NEW.username, '')), 'A') ||
            setweight(to_tsvector('english', COALESCE(NEW.firstname, '')), 'B') ||
            setweight(to_tsvector('english', COALESCE(NEW.surname, '')), 'B') ||
            setweight(to_tsvector('english', COALESCE(NEW.type, '')), 'C')
        );
    END IF;

    -- Update the tsvector for existing records if relevant fields change
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.username <> OLD.username OR NEW.firstname <> OLD.firstname OR
            NEW.surname <> OLD.surname OR NEW.type <> OLD.type) THEN
            NEW.tsvectors := (
                setweight(to_tsvector('english', COALESCE(NEW.username, '')), 'A') ||
                setweight(to_tsvector('english', COALESCE(NEW.firstname, '')), 'B') ||
                setweight(to_tsvector('english', COALESCE(NEW.surname, '')), 'B') ||
                setweight(to_tsvector('english', COALESCE(NEW.type, '')), 'C')
            );
        END IF;
    END IF;

    RETURN NEW;
END $$ LANGUAGE plpgsql;

-- Create a trigger before insert or update on user
CREATE TRIGGER user_search_update
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE PROCEDURE user_search_update();

-- Create a GIN index for ts_vectors.
CREATE INDEX search_user ON users USING GIN (tsvectors);


---- IDX05
-- Add column to groups to store computed ts_vectors.
ALTER TABLE groups
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
CREATE FUNCTION groups_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.name), 'A') ||
         setweight(to_tsvector('english', NEW.description), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english', NEW.name), 'A') ||
             setweight(to_tsvector('english', NEW.description), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on groups.
CREATE TRIGGER groups_search_update
 BEFORE INSERT OR UPDATE ON groups
 FOR EACH ROW
 EXECUTE PROCEDURE groups_search_update();


-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx ON groups USING GIN (tsvectors);


---- IDX06
-- Add column to post to store computed ts_vectors.
ALTER TABLE post
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = to_tsvector('english', NEW.description);
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.description <> OLD.description) THEN
         NEW.tsvectors = to_tsvector('english', NEW.description);
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on post.
CREATE TRIGGER post_search_update
 BEFORE INSERT OR UPDATE ON post
 FOR EACH ROW
 EXECUTE PROCEDURE post_search_update();


-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx ON post USING GIN (tsvectors);


---- IDX07
-- Add column to comment to store computed ts_vectors.
ALTER TABLE comment
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
CREATE FUNCTION comment_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = to_tsvector('english', NEW.content);
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.content <> OLD.content) THEN
         NEW.tsvectors = to_tsvector('english', NEW.content);
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on comment.
CREATE TRIGGER comment_search_update
 BEFORE INSERT OR UPDATE ON comment
 FOR EACH ROW
 EXECUTE PROCEDURE comment_search_update();


-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx ON comment USING GIN (tsvectors);
