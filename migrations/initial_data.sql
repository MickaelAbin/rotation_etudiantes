-- Initial DML for project clinical_rotations
USE clinical_rotations;

-- Insert academic_levels
INSERT INTO academic_levels (id, label) VALUES (2, 'Vet 2');
INSERT INTO academic_levels (id, label) VALUES (3, 'Vet 3');
INSERT INTO academic_levels (id, label) VALUES (4, 'Vet 4');
INSERT INTO academic_levels (id, label) VALUES (5, 'Vet 5');
INSERT INTO academic_levels (id, label) VALUES (6, 'Vet 6');

-- Insert clinical_rotation_categories
-- Vet 2
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (2, 'Urgences nuit', '18:00:00', '22:00:00', 1, 0, null); 
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (2, 'Urgences nuit WE', '18:00:00', '22:00:00', 1, 1, null); 
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (2, 'SI nuit', '18:00:00', '22:00:00', 1, 0, null); 
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (2, 'SI nuit WE', '18:00:00', '22:00:00', 1, 1, null); 

-- Vet 3
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (3, 'Équine nuit', '18:00:00', '22:00:00', 2, 0, null); 
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (3, 'Équine jour WE', '09:00:00', '12:00:00', 2, 1, null);
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (3, 'Équine nuit WE', '18:00:00', '22:00:00', 2, 1, null); 

-- Vet 4
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (4, 'Urgences nuit', '18:00:00', '24:00:00', 1, 0, null); 
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (4, 'Urgences jour WE', '08:30:00', '18:00:00', 1, 1, null);
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (4, 'Urgences nuit WE', '18:00:00', '24:00:00', 1, 1, null); 
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (4, 'SI nuit', '18:00:00', '24:00:00', 1, 0, null); 
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (4, 'SI jour WE', '08:30:00', '18:00:00', 2, 1, null);
INSERT INTO clinical_rotation_categories (academic_level_id, label, start_time, end_time, nb_students, is_on_weekend, color)
    VALUES (4, 'SI nuit WE', '18:00:00', '24:00:00', 1, 1, null);
