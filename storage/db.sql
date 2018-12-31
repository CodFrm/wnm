CREATE TABLE config
(
  key   varchar PRIMARY KEY NOT NULL,
  value varchar             NOT NULL,
  time  bigint              NOT NULL
);
CREATE TABLE plugin
(
  package    varchar primary key not null,
  name       varchar             not null,
  path       varchar(1024)       not null,
  source     integer             not null,
  is_install integer default 0 not null,
  is_enable  integer default 0 not null
);