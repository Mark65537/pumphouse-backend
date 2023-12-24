CREATE TABLE tarifs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    period_id INT NOT NULL,
    amount_rub FLOAT NOT NULL,
    UNIQUE INDEX period_unique (period_id),
    CONSTRAINT fk_tarifs_periods FOREIGN KEY (period_id) REFERENCES periods (id)
);