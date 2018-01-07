
CREATE TABLE measure (
  id_measure int(11) NOT NULL,
  nm_topic varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  vl_topic int(11) NOT NULL,
  dt_measure datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE topic (
  id_topic int(11) NOT NULL,
  nm_topic varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  id_active varchar(1) COLLATE utf8_unicode_ci NOT NULL
);

ALTER TABLE measure ADD PRIMARY KEY (id_measure);
ALTER TABLE topic  ADD PRIMARY KEY (id_topic);

ALTER TABLE measure  MODIFY id_measure int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE topic  MODIFY id_topic int(11) NOT NULL AUTO_INCREMENT;

