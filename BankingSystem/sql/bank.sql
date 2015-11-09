drop database if exists cs4773;
create database cs4773;
use cs4773;

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

create table Accounts(
    accountID        integer primary key auto_increment,
    profileID        integer ,
    SSN              int not NULL unique,
    foreign key (profileID) references Profiles (profileID) 
);

insert into Profiles(firstName, email)
	VALUES("firstGuy", "first@email.com")
insert into Profiles(firstName, email)
	VALUES("secondGuy", "second@email.com")

insert into Accounts(profileID)
	values (7)
insert into Accounts(profileID)
	values (null)
	
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, profileType)
    values ('Bob', null, 'Roberts', 'bob@email.com', '210-555-2364', 'male', '214 De Zevala, San Antonio, TX 78249', '1987-02-14', 'member');
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, profileType)
    values ('Sarah', 'Katherine', 'Kinsberg', 'sarah@email.com', '210-555-5887', 'female', '536 Lasseter Dr, MO 63044', '1982-10-01', 'member');
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, profileType)
    values ('Daniel', 'Jonathan', 'Jacobson', 'daniel@email.com', '210-555-1059', 'male', '482 Sugar Ln, San Antonio, TX 78249', '1992-11-12', 'employee');
