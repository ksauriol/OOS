drop database if exists cs4773;
create database cs4773;
use cs4773;

-- a 'BankTable' will exist, which contains information on members.
-- a member must have an entry in the BankTable in order to register
-- and create an entry in the Profiles table.
-- the BankTable contains all the normal information a bank would have,
-- such as first/last name, address, SSN, bank(account/member)ID, etc.

-- this table's tuples are created during user registration.
-- the important fields here are bankID (which IDs members), SSN,
-- userName, email, and password. SSN and bankID are verified during
-- registration. The userName will be checked to see if it's taken.
-- This Profiles table is what will be referenced when they log in,
-- or when they send us requests (maybe).
create table Profiles(
    profileID       integer primary key auto_increment,
    firstName       varchar(50),
    middleName      varchar(50),
    lastName        varchar(50),
    email           varchar(50) unique,
    phone           varchar(15),
    gender          varchar(6),
    address         varchar(100),
    dob             date,
    temp			varchar(50),
    timeOfTemp		int,
    password		varchar(50),
    SSN             int (11) NOT NULL unique
);

-- This table's entries represent a member's accounts, e.g. checkings/savings/investment/etc. (I think)
create table Accounts(
    accountID        integer primary key auto_increment,
    profileID        integer,
    SSN              int not NULL unique,
    foreign key (profileID) references Profiles (profileID) 
);

create table GPSData(
    gpsID           integer primary key auto_increment,
    profileID       integer not null,
    longitude       varchar(50) not null,
    latitude        varchar(50) not null,
    altitude        varchar(50) not null,
    dateAndTime     datetime not null,
    foreign key (profileID) references Profiles (profileID),
    unique (profileID, dateAndTime)
);

insert into Profiles(firstName, email, SSN)
	VALUES("firstGuy", "first@email.com", 219543819);
insert into Profiles(firstName, email, SSN)
	VALUES("secondGuy", "second@email.com", 495829184);

insert into Accounts(profileID, SSN)
	values (1, 219543819);
insert into Accounts(profileID, SSN)
	values (2, 495829184);
	
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, SSN)
    values ('Bob', null, 'Roberts', 'bob@email.com', '210-555-2364', 'male', '214 De Zevala, San Antonio, TX 78249', '1987-02-14', 402858492);
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, SSN)
    values ('Sarah', 'Katherine', 'Kinsberg', 'sarah@email.com', '210-555-5887', 'female', '536 Lasseter Dr, MO 63044', '1982-10-01', 4039810595);
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, SSN)
    values ('Daniel', 'Jonathan', 'Jacobson', 'daniel@email.com', '210-555-1059', 'male', '482 Sugar Ln, San Antonio, TX 78249', '1992-11-12', 694968483);

update Profiles set password = 'pass123' where email = 'bob@email.com';
