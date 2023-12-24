CREATE TABLE deleted_residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_id INT NOT NULL,
    period_id INT NOT NULL,
    UNIQUE INDEX resident_unique (resident_id),
    CONSTRAINT fk_deleted_residents_residents FOREIGN KEY (resident_id) REFERENCES residents (id),
    CONSTRAINT fk_deleted_residents_periods FOREIGN KEY (period_id) REFERENCES periods (id)
);