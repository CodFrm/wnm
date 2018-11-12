CREATE TABLE config
(
    key varchar PRIMARY KEY NOT NULL,
    value varchar NOT NULL,
    time bigint NOT NULL
);
CREATE TABLE plugin
(
    package varchar PRIMARY KEY NOT NULL,
    name varchar NOT NULL,
    namespace varchar NOT NULL
);