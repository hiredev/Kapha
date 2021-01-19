
    DROP TABLE `wesdap_yoga`.`user`;
    RENAME TABLE `wesdap_yoga`.`usuario` TO `wesdap_yoga`.`user`;

    ALTER TABLE alumno DROP FOREIGN KEY FK_1435D52DA76ED395;
    DROP INDEX UNIQ_1435D52DDB38439E ON alumno;
    ALTER TABLE alumno CHANGE usuario_id user_id INT NOT NULL;
    ALTER TABLE alumno ADD CONSTRAINT FK_1435D52DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
    CREATE UNIQUE INDEX UNIQ_1435D52DA76ED395 ON alumno (user_id);
    ALTER TABLE maestro DROP FOREIGN KEY FK_F017EDAA76ED395;
    DROP INDEX UNIQ_F017EDADB38439E ON maestro;
    ALTER TABLE maestro CHANGE usuario_id user_id INT NOT NULL;
    ALTER TABLE maestro ADD CONSTRAINT FK_F017EDAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
    CREATE UNIQUE INDEX UNIQ_F017EDAA76ED395 ON maestro (user_id);
    ALTER TABLE user RENAME INDEX uniq_2265b05de7927c74 TO UNIQ_8D93D649E7927C74;

    RENAME TABLE `wesdap_yoga`.`alumno` TO `wesdap_yoga`.`student`;
    RENAME TABLE `wesdap_yoga`.`aula_alumno` TO `wesdap_yoga`.`aula_student`;

    ALTER TABLE aula_student DROP FOREIGN KEY FK_3CC1898DFC28E5EE;
    DROP INDEX IDX_3CC1898DFC28E5EE ON aula_student;
    ALTER TABLE aula_student DROP PRIMARY KEY;
    ALTER TABLE aula_student CHANGE alumno_id student_id INT NOT NULL;
    ALTER TABLE aula_student ADD CONSTRAINT FK_61DDF887CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE;
    CREATE INDEX IDX_61DDF887CB944F1A ON aula_student (student_id);
    ALTER TABLE aula_student ADD PRIMARY KEY (aula_id, student_id);
    ALTER TABLE aula_student RENAME INDEX idx_3cc1898dad1a1255 TO IDX_61DDF887AD1A1255;
    ALTER TABLE pago DROP FOREIGN KEY FK_F4DF5F3EFC28E5EE;
    DROP INDEX IDX_F4DF5F3EFC28E5EE ON pago;
    ALTER TABLE pago CHANGE alumno_id student_id INT NOT NULL;
    ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3ECB944F1A FOREIGN KEY (student_id) REFERENCES student (id);
    CREATE INDEX IDX_F4DF5F3ECB944F1A ON pago (student_id);
    ALTER TABLE student RENAME INDEX uniq_1435d52da76ed395 TO UNIQ_B723AF33A76ED395;

    RENAME TABLE `wesdap_yoga`.`pago` TO `wesdap_yoga`.`payment`;

    ALTER TABLE payment DROP FOREIGN KEY FK_F4DF5F3EFC28E5EE;
    DROP INDEX IDX_F4DF5F3EFC28E5EE ON payment;
    ALTER TABLE payment CHANGE alumno_id student_id INT NOT NULL;
    ALTER TABLE payment ADD CONSTRAINT FK_6D28840DCB944F1A FOREIGN KEY (student_id) REFERENCES student (id);
    CREATE INDEX IDX_6D28840DCB944F1A ON payment (student_id);
    ALTER TABLE student RENAME INDEX uniq_1435d52da76ed395 TO UNIQ_B723AF33A76ED395;


    RENAME TABLE `wesdap_yoga`.`maestro` TO `wesdap_yoga`.`teacher`;


    ALTER TABLE aula DROP FOREIGN KEY FK_31990A420E41137;
    DROP INDEX IDX_31990A420E41137 ON aula;
    ALTER TABLE aula CHANGE maestro_id teacher_id INT NOT NULL;
    ALTER TABLE aula ADD CONSTRAINT FK_31990A441807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id);
    CREATE INDEX IDX_31990A441807E1D ON aula (teacher_id);
    ALTER TABLE cobranza DROP FOREIGN KEY FK_AE20EF3D20E41137;
    DROP INDEX IDX_AE20EF3D20E41137 ON cobranza;
    ALTER TABLE cobranza CHANGE maestro_id teacher_id INT NOT NULL;
    ALTER TABLE cobranza ADD CONSTRAINT FK_AE20EF3D41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id);
    CREATE INDEX IDX_AE20EF3D41807E1D ON cobranza (teacher_id);
    ALTER TABLE teacher RENAME INDEX uniq_f017edaa76ed395 TO UNIQ_B0F6A6D5A76ED395;
    ALTER TABLE teacher RENAME INDEX idx_f017eda3397707a TO IDX_B0F6A6D53397707A;

    RENAME TABLE `wesdap_yoga`.`aula` TO `wesdap_yoga`.`lesson`;
    RENAME TABLE `wesdap_yoga`.`aula_student` TO `wesdap_yoga`.`lesson_student`;


    ALTER TABLE lesson RENAME INDEX idx_31990a441807e1d TO IDX_F87474F341807E1D;
    ALTER TABLE lesson RENAME INDEX idx_31990a4fd8a7328 TO IDX_F87474F3FD8A7328;
    ALTER TABLE lesson_student DROP FOREIGN KEY FK_3CC1898DAD1A1255;
    DROP INDEX IDX_61DDF887AD1A1255 ON lesson_student;
    ALTER TABLE lesson_student DROP PRIMARY KEY;
    ALTER TABLE lesson_student CHANGE aula_id lesson_id INT NOT NULL;
    ALTER TABLE lesson_student ADD CONSTRAINT FK_425FFD94CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE;
    CREATE INDEX IDX_425FFD94CDF80196 ON lesson_student (lesson_id);
    ALTER TABLE lesson_student ADD PRIMARY KEY (lesson_id, student_id);
    ALTER TABLE lesson_student RENAME INDEX idx_61ddf887cb944f1a TO IDX_425FFD94CB944F1A;

    RENAME TABLE `wesdap_yoga`.`programa` TO `wesdap_yoga`.`course`;

    ALTER TABLE lesson DROP FOREIGN KEY FK_31990A4FD8A7328;
    DROP INDEX IDX_F87474F3FD8A7328 ON lesson;
    ALTER TABLE lesson CHANGE programa_id course_id INT NOT NULL;
    ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3591CC992 FOREIGN KEY (course_id) REFERENCES course (id);
    CREATE INDEX IDX_F87474F3591CC992 ON lesson (course_id);

    ALTER TABLE `teacher` CHANGE `nombre` `first_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `apellido` `last_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
    ALTER TABLE `student` CHANGE `nombre` `first_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `apellido` `last_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

    RENAME TABLE `wesdap_yoga`.`cobranza` TO `wesdap_yoga`.`payout`;

    ALTER TABLE lesson CHANGE fecha date DATETIME NOT NULL;
    ALTER TABLE payout RENAME INDEX idx_ae20ef3d41807e1d TO IDX_4E2EA90241807E1D;
    
    ALTER TABLE user ADD default_locale VARCHAR(2) DEFAULT NULL, CHANGE fecha date DATETIME NOT NULL;
    ALTER TABLE course CHANGE titulo title VARCHAR(255) NOT NULL, CHANGE descripcion description LONGTEXT NOT NULL;

    ALTER TABLE payment CHANGE monto amount DOUBLE PRECISION NOT NULL, CHANGE metodo method VARCHAR(30) NOT NULL, CHANGE transaccion transaction LONGTEXT NOT NULL, CHANGE fecha date DATETIME NOT NULL;

    CREATE TABLE payment_plan (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, description LONGTEXT NOT NULL, amount DOUBLE PRECISION NOT NULL, period INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

    ALTER TABLE payment ADD plan_id INT DEFAULT NULL;
    ALTER TABLE payment ADD CONSTRAINT FK_6D28840DE899029B FOREIGN KEY (plan_id) REFERENCES payment_plan (id);
    CREATE INDEX IDX_6D28840DE899029B ON payment (plan_id);

    ALTER TABLE cuenta_zoom CHANGE access_token access_token VARCHAR(999) NOT NULL, CHANGE refresh_token refresh_token VARCHAR(999) NOT NULL;

    ALTER TABLE course ADD is_active TINYINT(1) NOT NULL, CHANGE fecha date DATETIME NOT NULL;
    ALTER TABLE teacher ADD is_active TINYINT(1) NOT NULL;
    ALTER TABLE lesson ADD is_active TINYINT(1) NOT NULL;

    ALTER TABLE payment ADD payload LONGTEXT NOT NULL;

    ALTER TABLE teacher ADD imagen VARCHAR(255) DEFAULT NULL, ADD biography LONGTEXT NOT NULL;
    ALTER TABLE lesson ADD zoom_payload LONGTEXT NOT NULL;

