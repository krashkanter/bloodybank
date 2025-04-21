-- Ensure this runs on a database with the schema already created.
-- Use appropriate methods to hash passwords for the Users table in a real application.

-- Data for Blood_Bank Table (Independent)
INSERT INTO Blood_Bank (Name, Location, Quantity, Available_Blood_Groups)
VALUES ('City Central Blood Bank', '123 Main St, Anytown', 1500, 'A+, O+, B-, AB+'),
       ('County Regional Blood Center', '456 Oak Ave, Somewhere', 2500, 'O-, A-, B+, AB-'),
       ('Hope Blood Services', '789 Pine Ln, Villagetown', 800, 'A+, O+, O-, B+'),
       ('Unity Blood Bank', '101 Maple Dr, Metrocity', 3000, 'All Groups Available'),
       ('Coastal Blood Collection', '202 Beach Rd, Seaview', 1200, 'A-, O-, B-, AB-');

-- Data for Hospital Table (Independent)
INSERT INTO Hospital (Name, Location)
VALUES ('General Hospital', '1 Hospital Plaza, Anytown'),
       ('St. Luke\'s Medical Center', '500 Health Way, Somewhere'),
       ('Mercy Hospital', '999 Care Blvd, Villagetown'),
       ('Metro Health Clinic', '10 Urban St, Metrocity'),
       ('Seaside Medical', '303 Coast Ave, Seaview');

-- Data for Blood Table (Represents blood group types)
INSERT INTO Blood (Blood_Group)
VALUES ('A+'),
       ('O-'),
       ('B+'),
       ('AB-'),
       ('O+');
-- Note: You might want more distinct Blood groups here (e.g., A-, B-, AB+)
-- We'll assume these IDs correspond to the listed groups for subsequent inserts.
-- Let's assume ID 1=A+, 2=O-, 3=B+, 4=AB-, 5=O+ for FK usage below.

-- Data for Users Table (Independent)
-- IMPORTANT: Replace '<hashed_password_here>' with actual hashed passwords!
INSERT INTO Users (Username, Password, Role)
VALUES ('admin_city', '<hashed_password_1>', 'manager'),
       ('analyst_kenji', '<hashed_password_2>', 'analyst'),
       ('reg_maria', '<hashed_password_3>', 'registration'),
       ('manager_unity', '<hashed_password_4>', 'manager'),
       ('reg_lead_coast', '<hashed_password_5>', 'registration');

-- Data for Donor Table (Depends on Blood_Bank)
-- Assumes Blood_Bank IDs are 1 to 5 from above inserts.
INSERT INTO Donor (Name, Age, Gender, Blood_Group, Address, Contact, Disease, Date_of_Donation, Quantity_of_Blood,
                   Blood_Bank_ID)
VALUES ('Alice Johnson', 35, 'Female', 'A+', '1 Willow Way, Anytown', '555-1111', NULL, '2025-04-10', 450, 1),
       ('Bob Williams', 42, 'Male', 'O-', '2 Birch St, Somewhere', '555-2222', NULL, '2025-03-15', 500, 2),
       ('Charlie Brown', 28, 'Male', 'B+', '3 Cedar Rd, Villagetown', '555-3333', 'Controlled Diabetes', '2025-04-01',
        470, 3), -- Assuming this doesn't trigger block
       ('Diana Miller', 50, 'Female', 'AB-', '4 Elm Ct, Metrocity', '555-4444', NULL, '2025-02-20', 480, 4),
       ('Ethan Davis', 22, 'Male', 'O+', '5 Spruce Pl, Anytown', '555-5555', NULL, '2025-04-18', 490, 1);
-- Assumes Donor_IDs are 1 to 5 from above inserts.

-- Data for Patient Table (Depends on Hospital)
-- Assumes Hospital IDs are 1 to 5 from above inserts.
-- Note: Redundant 'Hospital' column is filled based on Hospital_ID.
INSERT INTO Patient (Name, Gender, Contact, Blood_Group, Address, Date_of_Intake, Hospital_ID, Age, Hospital, Reason)
VALUES ('Frank Green', 'Male', '555-6666', 'A+', '11 Oak St, Anytown', '2025-04-05', 1, 65, 'General Hospital',
        'Surgery Preparation'),
       ('Grace Hall', 'Female', '555-7777', 'O-', '12 Maple Dr, Somewhere', '2025-03-28', 2, 30,
        'St. Luke\'s Medical Center', 'Accident Trauma'),
       ('Henry Lewis', 'Male', '555-8888', 'B+', '13 Pine Ln, Villagetown', '2025-04-12', 3, 72, 'Mercy Hospital',
        'Severe Anemia'),
       ('Ivy Martin', 'Female', '555-9999', 'AB-', '14 Birch Ave, Metrocity', '2025-04-15', 4, 45,
        'Metro Health Clinic', 'Chemotherapy Support'),
       ('Jack Nelson', 'Male', '555-0000', 'O+', '15 Cedar Rd, Seaview', '2025-04-02', 5, 58, 'Seaside Medical',
        'Chronic Condition Management');
-- Assumes Patient_IDs are 1 to 5 from above inserts.

-- Data for Clinical_Analyst Table (Depends on Blood_Bank)
-- Assumes Blood_Bank IDs are 1 to 5.
INSERT INTO Clinical_Analyst (Name, Blood_Bank_ID)
VALUES ('Dr. Evelyn Reed', 1),
       ('Kenji Tanaka', 2),
       ('Maria Garcia', 3),
       ('Sam Patel', 4),
       ('Chloe Dubois', 1);
-- Assumes Clinical_Analyst IDs are 1 to 5 from above inserts.

-- Data for Registration_Team Table (Depends on Blood_Bank)
-- Assumes Blood_Bank IDs are 1 to 5.
INSERT INTO Registration_Team (Name, Blood_Bank_ID)
VALUES ('Registration Desk A', 1),
       ('Welcome Team B', 2),
       ('Intake Specialists', 3),
       ('Donor Services Unit', 4),
       ('Patient Check-in Staff', 1);
-- Assumes Registration_Team IDs are 1 to 5 from above inserts.

-- Data for Manager Table (Depends on Blood_Bank)
-- Assumes Blood_Bank IDs are 1 to 5.
INSERT INTO Manager (Name, Location, Blood_Bank_ID)
VALUES ('Sarah Chen', 'Anytown Office', 1),
       ('David Rodriguez', 'Somewhere Center', 2),
       ('Fatima Khan', 'Villagetown Branch', 3),
       ('Michael Lee', 'Metrocity HQ', 4),
       ('Olivia Baker', 'Seaview Annex', 5);
-- Assumes Manager IDs are 1 to 5 from above inserts.

-- Data for Donation Table (Depends on Donor and Blood)
-- Assumes Donor IDs 1-5 and Blood IDs 1-5 matching donor blood group.
INSERT INTO Donation (Donor_ID, Blood_ID, Date, Quantity)
VALUES (1, 1, '2025-04-10', 450), -- Alice (A+), Blood ID 1 (A+)
       (2, 2, '2025-03-15', 500), -- Bob (O-), Blood ID 2 (O-)
       (3, 3, '2025-04-01', 470), -- Charlie (B+), Blood ID 3 (B+)
       (4, 4, '2025-02-20', 480), -- Diana (AB-), Blood ID 4 (AB-)
       (5, 5, '2025-04-18', 490);
-- Ethan (O+), Blood ID 5 (O+)

-- Data for Transfusion Table (Depends on Patient and Blood)
-- Assumes Patient IDs 1-5 and Blood IDs 1-5 matching patient blood group.
INSERT INTO Transfusion (Patient_ID, Blood_ID, Date)
VALUES (1, 1, '2025-04-08'), -- Frank (A+), Blood ID 1 (A+)
       (2, 2, '2025-03-30'), -- Grace (O-), Blood ID 2 (O-)
       (3, 3, '2025-04-14'), -- Henry (B+), Blood ID 3 (B+)
       (4, 4, '2025-04-18'), -- Ivy (AB-), Blood ID 4 (AB-)
       (5, 5, '2025-04-05');
-- Jack (O+), Blood ID 5 (O+)

-- Data for Blood_Analysis Table (Depends on Blood and Clinical_Analyst)
-- Assumes Blood IDs 1-5 and Analyst IDs 1-5.
INSERT INTO Blood_Analysis (Blood_ID, Analyst_ID, Analysis_Date)
VALUES (1, 1, '2025-04-10'), -- Blood A+ analyzed by Dr. Reed
       (2, 2, '2025-03-16'), -- Blood O- analyzed by Kenji
       (3, 3, '2025-04-02'), -- Blood B+ analyzed by Maria
       (4, 4, '2025-02-21'), -- Blood AB- analyzed by Sam
       (5, 5, '2025-04-19');
-- Blood O+ analyzed by Chloe

-- Data for Donor_Registration Table (Depends on Donor and Registration_Team)
-- Assumes Donor IDs 1-5 and Team IDs 1-5.
INSERT INTO Donor_Registration (Donor_ID, Team_ID)
VALUES (1, 1), -- Alice registered by Desk A
       (2, 2), -- Bob registered by Team B
       (3, 3), -- Charlie registered by Intake Specialists
       (4, 4), -- Diana registered by Donor Services
       (5, 5);
-- Ethan registered by Patient Check-in (Maybe error in logic, adjust Team ID if needed)

-- Data for Patient_Registration Table (Depends on Patient and Registration_Team)
-- Assumes Patient IDs 1-5 and Team IDs 1-5.
INSERT INTO Patient_Registration (Patient_ID, Team_ID)
VALUES (1, 1), -- Frank registered by Desk A
       (2, 2), -- Grace registered by Team B
       (3, 3), -- Henry registered by Intake Specialists
       (4, 4), -- Ivy registered by Donor Services (Maybe error in logic, adjust Team ID if needed)
       (5, 5);
-- Jack registered by Patient Check-in

-- Data for Blood_Bank_Groups Table (Depends on Blood_Bank)
-- Assumes Bank IDs 1-5.
INSERT INTO Blood_Bank_Groups (Bank_ID, Blood_Group)
VALUES (1, 'A+'),
       (1, 'O+'),
       (2, 'O-'),
       (3, 'B+'),
       (4, 'AB-');

-- Data for Contact_Messages Table (Independent)
INSERT INTO Contact_Messages (Name, Email, Subject, Message, Timestamp)
VALUES ('John Smith', 'john.s@email.com', 'Donation Inquiry', 'When is the next blood drive?', NOW()),
       ('Jane Doe', 'jane.d@email.com', 'Question about eligibility', 'I have a tattoo, can I donate?',
        NOW() - INTERVAL 1 DAY),
       ('Peter Jones', 'peter.j@email.com', 'Website Feedback', 'The location map is hard to read.',
        NOW() - INTERVAL 2 DAY),
       ('Mary White', 'mary.w@email.com', 'Appointment Change', 'Need to reschedule my donation for next week.',
        NOW() - INTERVAL 3 DAY),
       ('Chris Green', 'chris.g@email.com', 'Thank You Note',
        'Just wanted to thank the staff at City Central for their kindness during my donation.',
        NOW() - INTERVAL 4 DAY);

-- Data for Hospital_Requests Table (Independent, but logically linked to Hospital)
INSERT INTO Hospital_Requests (Hospital_Name, Contact_Person, Phone, Blood_Group, Units_Requested, Reason, Status,
                               Request_Date)
VALUES ('General Hospital', 'Nurse Ratched', '555-1001', 'O-', 10, 'Emergency Surgery', 'Pending', NOW()),
       ('St. Luke\'s Medical Center', 'Dr. Carter', '555-2002', 'A+', 5, 'Scheduled Transfusion', 'Approved',
        NOW() - INTERVAL 1 DAY),
       ('Mercy Hospital', 'Supply Chain Dept', '555-3003', 'B+', 8, 'Trauma Patient Stock', 'Fulfilled',
        NOW() - INTERVAL 2 DAY),
       ('Metro Health Clinic', 'Oncology Ward', '555-4004', 'AB-', 2, 'Oncology Patient Needs', 'Pending', NOW()),
       ('Seaside Medical', 'ER Charge Nurse', '555-5005', 'O+', 15, 'Multiple Accident Victims', 'Approved',
        NOW() - INTERVAL 3 DAY);