
CREATE TABLE measure (
  id_measure int(11) NOT NULL,
  nm_topic varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  vl_topic float(11,2) NOT NULL,
  dt_measure datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE topic (
  id_topic int(11) NOT NULL,
  nm_mqtt_server varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  nu_mqtt_port int(11) NOT NULL,
  nm_topic varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  nm_element varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  id_active varchar(1) COLLATE utf8_unicode_ci NOT NULL
);

ALTER TABLE measure ADD PRIMARY KEY (id_measure);
ALTER TABLE topic  ADD PRIMARY KEY (id_topic);

ALTER TABLE measure  MODIFY id_measure int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE topic  MODIFY id_topic int(11) NOT NULL AUTO_INCREMENT;

-- Optimizing history table
CREATE TABLE measuretemp
  SELECT dt_measure  dt_measure, nm_topic,  format(avg(vl_topic),2) vl_topic FROM measure 
   WHERE dt_measure <= NOW() - INTERVAL 2 DAY
   GROUP BY nm_topic, DATE_FORMAT(dt_measure, '%Y/%m/%d %H:00');

DELETE FROM measure WHERE dt_measure <= NOW() - INTERVAL 2 DAY;

INSERT INTO measure (dt_measure, nm_topic, vl_topic)
select dt_measure, nm_topic, vl_topic from measuretemp;

DROP TABLE measuretemp;
