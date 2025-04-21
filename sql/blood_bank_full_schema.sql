-- Filename: blood_bank_full_schema_fixed.sql

-- DROP TABLES (Optional, for re-creation)
DROP TABLE IF EXISTS Donor_Registration, Patient_Registration, Blood_Analysis, Transfusion, Donation, Manager,
Registration_Team, Clinical_Analyst, Blood, Patient, Hospital, Donor, Blood_Bank_Groups, Blood_Bank, Contact_Messages, Hospital_Requests, Users;

-- CREATE TABLES

CREATE TABLE Blood_Bank (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Location VARCHAR(100),
    Quantity INT,
    Available_Blood_Groups TEXT
);
ALTER TABLE Blood_Bank MODIFY ID INT NOT NULL AUTO_INCREMENT;

CREATE TABLE Donor (
    Donor_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Age INT,
    Gender VARCHAR(10),
    Blood_Group VARCHAR(5),
    Address TEXT,
    Contact VARCHAR(15),
    Disease VARCHAR(100),
    Date_of_Donation DATE,
    Quantity_of_Blood INT,
    Blood_Bank_ID INT,
    FOREIGN KEY (Blood_Bank_ID) REFERENCES Blood_Bank(ID) ON DELETE CASCADE ON UPDATE CASCADE
);
ALTER TABLE Donor MODIFY Donor_ID INT NOT NULL AUTO_INCREMENT;

CREATE TABLE Hospital (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Location VARCHAR(100)
);
ALTER TABLE Hospital MODIFY ID INT NOT NULL AUTO_INCREMENT;

CREATE TABLE Patient (
    Patient_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Gender VARCHAR(10),
    Contact VARCHAR(15),
    Blood_Group VARCHAR(5),
    Address TEXT,
    Date_of_Intake DATE,
    Hospital_ID INT,
    Age INT,
    Hospital VARCHAR(100),
    Reason TEXT,
    FOREIGN KEY (Hospital_ID) REFERENCES Hospital(ID) ON DELETE CASCADE ON UPDATE CASCADE
);
ALTER TABLE Patient MODIFY Patient_ID INT NOT NULL AUTO_INCREMENT;

CREATE TABLE Blood (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Blood_Group VARCHAR(5)
);
ALTER TABLE Blood MODIFY ID INT NOT NULL AUTO_INCREMENT;

CREATE TABLE Clinical_Analyst (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Blood_Bank_ID INT,
    FOREIGN KEY (Blood_Bank_ID) REFERENCES Blood_Bank(ID) ON DELETE CASCADE ON UPDATE CASCADE
);
ALTER TABLE Clinical_Analyst MODIFY ID INT NOT NULL AUTO_INCREMENT;

CREATE TABLE Registration_Team (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Blood_Bank_ID INT,
    FOREIGN KEY (Blood_Bank_ID) REFERENCES Blood_Bank(ID) ON DELETE CASCADE ON UPDATE CASCADE
);
ALTER TABLE Registration_Team MODIFY ID INT NOT NULL AUTO_INCREMENT;

CREATE TABLE Manager (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Location VARCHAR(100),
    Blood_Bank_ID INT,
    FOREIGN KEY (Blood_Bank_ID) REFERENCES Blood_Bank(ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Donation (
    Donation_ID INT AUTO_INCREMENT PRIMARY KEY,
    Donor_ID INT NULL,
    Blood_ID INT,
    Date DATE,
    Quantity INT,
    FOREIGN KEY (Donor_ID) REFERENCES Donor(Donor_ID),
    FOREIGN KEY (Blood_ID) REFERENCES Blood(ID)
);

CREATE TABLE Transfusion (
    Transfusion_ID INT AUTO_INCREMENT PRIMARY KEY,
    Patient_ID INT,
    Blood_ID INT,
    Date DATE,
    FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID),
    FOREIGN KEY (Blood_ID) REFERENCES Blood(ID)
);

CREATE TABLE Blood_Analysis (
    Analysis_ID INT AUTO_INCREMENT PRIMARY KEY,
    Blood_ID INT,
    Analyst_ID INT,
    Analysis_Date DATE,
    FOREIGN KEY (Blood_ID) REFERENCES Blood(ID),
    FOREIGN KEY (Analyst_ID) REFERENCES Clinical_Analyst(ID)
);

CREATE TABLE Donor_Registration (
    Donor_ID INT,
    Team_ID INT,
    PRIMARY KEY (Donor_ID, Team_ID),
    FOREIGN KEY (Donor_ID) REFERENCES Donor(Donor_ID),
    FOREIGN KEY (Team_ID) REFERENCES Registration_Team(ID)
);

CREATE TABLE Patient_Registration (
    Patient_ID INT,
    Team_ID INT,
    PRIMARY KEY (Patient_ID, Team_ID),
    FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID),
    FOREIGN KEY (Team_ID) REFERENCES Registration_Team(ID)
);

CREATE TABLE Blood_Bank_Groups (
    Bank_ID INT,
    Blood_Group VARCHAR(5),
    FOREIGN KEY (Bank_ID) REFERENCES Blood_Bank(ID)
);

CREATE TABLE Contact_Messages (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Email VARCHAR(100),
    Subject VARCHAR(200),
    Message TEXT,
    Timestamp DATETIME
);

CREATE TABLE Hospital_Requests (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Hospital_Name VARCHAR(100),
    Contact_Person VARCHAR(100),
    Phone VARCHAR(15),
    Blood_Group VARCHAR(5),
    Units_Requested INT,
    Reason TEXT,
    Status VARCHAR(20),
    Request_Date DATETIME
);

CREATE TABLE Users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Role VARCHAR(20) NOT NULL
);

-- VIEWS
CREATE VIEW Active_Donors AS
SELECT * FROM Donor
WHERE Date_of_Donation >= CURDATE() - INTERVAL 6 MONTH;

-- TRIGGERS
DELIMITER //
CREATE TRIGGER prevent_risky_donation
BEFORE INSERT ON Donor
FOR EACH ROW
BEGIN
    IF NEW.Disease IN ('Anemia', 'Hypertension') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Donor with disqualifying disease cannot donate blood';
    END IF;
END;
//
DELIMITER ;

-- ADVANCED QUERIES

-- 1. Donors with rare blood groups (not found in patients)
SELECT Name, Blood_Group
FROM Donor d
WHERE NOT EXISTS (
    SELECT 1
    FROM Patient p
    WHERE p.Blood_Group = d.Blood_Group
);

-- 2. Patients in the hospital with the least donations
SELECT p.Name, h.Name AS Hospital
FROM Patient p
JOIN Hospital h ON p.Hospital_ID = h.ID
WHERE h.ID = (
    SELECT h2.ID
    FROM Hospital h2
    JOIN Patient p2 ON h2.ID = p2.Hospital_ID
    GROUP BY h2.ID
    ORDER BY COUNT(*) ASC
    LIMIT 1
);

-- 3. Blood banks where all analysts work
SELECT Blood_Bank_ID
FROM Clinical_Analyst
GROUP BY Blood_Bank_ID
HAVING COUNT(*) = (
    SELECT COUNT(DISTINCT ID) FROM Clinical_Analyst
);

-- 4. Patients who received blood within 10 days of admission
SELECT p.Name
FROM Patient p
JOIN Transfusion t ON p.Patient_ID = t.Patient_ID
WHERE DATEDIFF(t.Date, p.Date_of_Intake) <= 10;

-- 5. Hospitals that have treated all blood groups
SELECT h.Name
FROM Hospital h
JOIN Patient p ON h.ID = p.Hospital_ID
GROUP BY h.ID
HAVING COUNT(DISTINCT p.Blood_Group) = (SELECT COUNT(DISTINCT Blood_Group) FROM Blood);

-- 6. Top donors per blood bank (most donations)
SELECT d.Name, bb.Name AS Blood_Bank, COUNT(*) AS Donations
FROM Donor d
JOIN Blood_Bank bb ON d.Blood_Bank_ID = bb.ID
JOIN Donation dn ON d.Donor_ID = dn.Donor_ID
GROUP BY d.Donor_ID
ORDER BY Donations DESC
LIMIT 5;

-- 7. Blood groups treated in each manager’s location
SELECT m.Name AS Manager, b.Blood_Group
FROM Manager m
JOIN Blood_Bank bb ON m.Blood_Bank_ID = bb.ID
JOIN Donor d ON d.Blood_Bank_ID = bb.ID
JOIN Blood b ON d.Blood_Group = b.Blood_Group;

-- 8. Analysts who analyzed blood from the busiest banks
SELECT ca.Name
FROM Clinical_Analyst ca
JOIN Blood_Analysis ba ON ca.ID = ba.Analyst_ID
WHERE ca.Blood_Bank_ID = (
    SELECT Blood_Bank_ID
    FROM Donor
    GROUP BY Blood_Bank_ID
    ORDER BY COUNT(*) DESC
    LIMIT 1
);

-- 9. Total quantity donated per blood group
SELECT Blood_Group, SUM(Quantity_of_Blood) AS Total_Donated
FROM Donor
GROUP BY Blood_Group;

-- 10. Top 2 hospitals with most patients having rare blood groups
SELECT h.Name, COUNT(*) AS RareGroupPatients
FROM Patient p
JOIN Hospital h ON p.Hospital_ID = h.ID
WHERE p.Blood_Group IN ('AB-', 'B-')
GROUP BY h.ID
ORDER BY RareGroupPatients DESC
LIMIT 2;

-- Sample User Insertion (hash password in PHP before inserting)
INSERT INTO Users (Username, Password, Role)
VALUES ('admin', '<hashed_password_here>', 'manager');
