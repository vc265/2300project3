CREATE TABLE users (
  id INTEGER PRIMARY KEY NOT NULL UNIQUE,
  firstname TEXT NOT NULL,
  lastname TEXT NOT NULL,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  session TEXT UNIQUE
);

INSERT INTO users (id, firstname, lastname, username, password) VALUES (1, 'Hello', 'World', 'hello_world', '$2y$10$ClgUb0.ZZEM2eaCzcAj5uOYKiVdZh1px2FrCXX70mpvYZoZG519zm'); /* password1 */
INSERT INTO users (id, firstname, lastname, username, password) VALUES (2, 'Goodbye', 'World', 'goodbye_world', '$2y$10$/dmfxeTS/6jziflEJUWT4ODSuHNLkCMdnCGqHS82uOQjVSgXtvbvu'); /* password2 */

CREATE TABLE images (
  id INTEGER PRIMARY KEY NOT NULL UNIQUE,
  user_id INTEGER NOT NULL,
  image TEXT NOT NULL,
  title TEXT NOT NULL UNIQUE,
  ext TEXT NOT NULL
);

INSERT INTO images (user_id, image, title, ext) VALUES (1, '1.jpg', 'mamamoo- yellow flower', 'jpg');  -- image taken from https://k2nblog.com/mini-album-mamamoo-yellow-flower/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (1, '2.jpg', 'mamamoo- memory', 'jpg'); -- image taken from https://k2nblog.com/mini-album-mamamoo-memory/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (1, '3.jpg', 'mamamoo- pink funky', 'jpg'); -- image taken from https://k2nblog.com/mini-album-mamamoo-pink-funky/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (2, '4.jpg', 'exo- exodus', 'jpg'); -- image taken from https://k2nblog.com/album-exo-the-2nd-album-exodus/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (2, '5.jpg', 'exo- exact', 'jpg'); -- image taken from https://k2nblog.com/album-exo-exact-the-3rd-album-korean-chinese-version/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (2, '6.jpg', 'got7- just right', 'jpg'); -- image taken from https://k2nblog.com/got7-just-right-3rd-mini-album/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (2, '7.jpg', 'got7- mad', 'jpg'); -- image taken from https://k2nblog.com/mini-album-got7-mad/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (1, '8.jpg', 'apink- pink memory', 'jpg'); -- image taken from https://k2nblog.com/album-apink-pink-memory-vol-2/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (1, '9.jpg', 'girls day- expectation', 'jpg'); -- image taken from https://k2nblog.com/album-girls-day-expectation-vol-1/ -->
INSERT INTO images (user_id, image, title, ext) VALUES (2, '10.jpg', 'big bang- made', 'jpg'); -- image taken from https://k2nblog.com/album-bigbang-made/ -->


CREATE TABLE tags (
  id INTEGER PRIMARY KEY NOT NULL UNIQUE,
  tag TEXT NOT NULL UNIQUE
);

INSERT INTO tags (tag) VALUES ('boyband');
INSERT INTO tags (tag) VALUES ('girlgroup');
INSERT INTO tags (tag) VALUES ('kpop');
INSERT INTO tags (tag) VALUES ('yg');
INSERT INTO tags (tag) VALUES ('sm');
INSERT INTO tags (tag) VALUES ('jyp');

CREATE TABLE gallery (
  id INTEGER PRIMARY KEY NOT NULL UNIQUE,
  image_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL
);

INSERT INTO gallery (image_id, tag_id) VALUES (1, 2);
INSERT INTO gallery (image_id, tag_id) VALUES (1, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (2, 2);
INSERT INTO gallery (image_id, tag_id) VALUES (2, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (3, 2);
INSERT INTO gallery (image_id, tag_id) VALUES (3, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (4, 1);
INSERT INTO gallery (image_id, tag_id) VALUES (4, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (4, 5);
INSERT INTO gallery (image_id, tag_id) VALUES (5, 1);
INSERT INTO gallery (image_id, tag_id) VALUES (5, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (5, 5);
INSERT INTO gallery (image_id, tag_id) VALUES (6, 1);
INSERT INTO gallery (image_id, tag_id) VALUES (6, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (6, 6);
INSERT INTO gallery (image_id, tag_id) VALUES (7, 1);
INSERT INTO gallery (image_id, tag_id) VALUES (7, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (7, 6);
INSERT INTO gallery (image_id, tag_id) VALUES (8, 2);
INSERT INTO gallery (image_id, tag_id) VALUES (8, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (9, 2);
INSERT INTO gallery (image_id, tag_id) VALUES (9, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (10, 1);
INSERT INTO gallery (image_id, tag_id) VALUES (10, 3);
INSERT INTO gallery (image_id, tag_id) VALUES (10, 4);
