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
    temp            varchar(50),
    timeOfTemp        int,
    password        varchar(50),
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

INSERT INTO Profiles VALUES
    (1,'steve',null,null,'email@email.com',null,null,null,'0000-00-00','password',0,'rowdy',170483472);

insert into Profiles(firstName, email, SSN)
    VALUES("firstGuy", "first@email.com", 219543819);
insert into Profiles(firstName, email, SSN)
    VALUES("secondGuy", "second@email.com", 495829184);
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, SSN)
    values ('Bob', null, 'Roberts', 'bob@email.com', '210-555-2364', 'male', '214 De Zevala, San Antonio, TX 78249', '1987-02-14', 402858492);
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, SSN)
    values ('Sarah', 'Katherine', 'Kinsberg', 'sarah@email.com', '210-555-5887', 'female', '536 Lasseter Dr, MO 63044', '1982-10-01', 4039810595);
insert into Profiles (firstName, middleName, lastName, email, phone, gender, address, dob, SSN)
    values ('Daniel', 'Jonathan', 'Jacobson', 'daniel@email.com', '210-555-1059', 'male', '482 Sugar Ln, San Antonio, TX 78249', '1992-11-12', 694968483);

INSERT INTO Profiles (firstName,middleName,lastName,email,phone,gender,address,dob,SSN) VALUES ("Rigel","Yardley","Duran","nulla.at.sem@aliquetmolestietellus.co.uk","154-725-7997","female","1490 Non, Ave","2016-04-03T11:43:51-07:00",961949116),("Dolan","Armando","Logan","urna.Ut@ipsum.edu","663-584-4076","male","671-7194 Egestas Avenue","2015-04-03T22:16:20-07:00",917255434),("Mohammad","Olivia","Reed","porta.elit.a@Praesentinterdumligula.edu","270-506-5951","female","Ap #752-8039 Felis Road","2016-01-12T19:27:56-08:00",927941167),("Fletcher","Tamekah","Norman","tempor.lorem@sociisnatoque.edu","944-526-6043","female","Ap #598-1631 Molestie Rd.","2016-04-07T03:12:27-07:00",908769869),("Alfonso","Brianna","Blevins","Vestibulum.ante.ipsum@eget.ca","498-109-0359","female","781-4660 Donec Rd.","2015-02-19T19:26:31-08:00",966831474),("Brent","Lacy","Hartman","Vivamus@Suspendissecommodotincidunt.org","459-398-9956","male","Ap #444-3761 Eu Avenue","2015-06-12T12:04:48-07:00",974094628),("Madeson","Nell","Herrera","dictum.cursus@interdum.co.uk","157-761-4147","male","955 Cubilia Avenue","2016-11-11T10:10:50-08:00",907698034),("Camden","TaShya","Burke","vel.vulputate.eu@Nullamenim.co.uk","650-187-5975","female","P.O. Box 947, 2471 Tristique St.","2014-12-10T20:45:53-08:00",917374174),("Mechelle","Lesley","Berry","penatibus.et@risus.edu","606-864-4757","female","461 Commodo Rd.","2015-12-15T21:17:35-08:00",932491851),("Isaiah","Cassady","Brooks","at.velit@nisinibh.org","740-486-5851","female","1284 Suspendisse Rd.","2016-02-21T09:34:20-08:00",946970884);
INSERT INTO Profiles (firstName,middleName,lastName,email,phone,gender,address,dob,SSN) VALUES ("Jillian","Christopher","Carlson","fringilla.mi@Quisqueimperdiet.edu","998-183-0591","female","9468 Amet Street","2016-05-01T18:18:36-07:00",987208930),("Shea","Walter","Lowe","sodales.purus.in@cursuspurus.org","262-509-9632","male","129-8759 Scelerisque St.","2015-07-05T13:58:16-07:00",979251365),("Nora","Erich","Cortez","sit.amet@Duiscursus.ca","824-436-2080","female","427-4572 Amet Rd.","2016-04-26T12:30:22-07:00",942337498),("Jacob","Wylie","Pate","Nulla@Proin.co.uk","326-555-6011","male","P.O. Box 523, 345 Nisi. Street","2015-01-07T09:52:10-08:00",996434153),("Joshua","Xena","Mccarthy","dolor.Nulla.semper@laciniaSed.ca","105-370-5758","male","Ap #235-2952 Risus. Rd.","2015-12-09T22:11:51-08:00",931648893),("Kadeem","Rhea","Freeman","est.Mauris@lacus.net","247-109-3989","male","P.O. Box 737, 5664 Suspendisse Avenue","2015-01-21T05:09:23-08:00",938354897),("Genevieve","Keane","House","magna@Phasellusdapibus.com","412-202-2653","female","Ap #179-5717 Accumsan Road","2015-01-16T05:02:47-08:00",919536588),("Ethan","Justin","Hall","molestie.tellus.Aenean@euismodet.net","819-727-9936","female","696-5416 Pede Rd.","2015-08-20T17:43:37-07:00",955812010),("Geraldine","Abraham","Spence","In.condimentum.Donec@risusNullaeget.net","968-434-7407","male","Ap #869-5519 Amet, Rd.","2015-02-06T10:56:59-08:00",908624639),("Joan","Jenna","Pierce","eu@dignissimlacusAliquam.net","852-560-2073","female","P.O. Box 555, 7446 Malesuada Street","2016-01-11T08:04:50-08:00",940327576);
INSERT INTO Profiles (firstName,middleName,lastName,email,phone,gender,address,dob,SSN) VALUES ("Sheila","Berk","Sims","Suspendisse.commodo.tincidunt@arcu.org","245-668-4171","male","2891 Pellentesque Avenue","2016-06-07T22:25:08-07:00",939256357),("Harriet","Gillian","Clark","ante@esttempor.edu","165-610-6916","female","P.O. Box 223, 7237 Massa. Av.","2016-03-04T18:33:47-08:00",950452228),("Kitra","Amelia","Garza","Quisque.ac.libero@erosNam.org","500-801-6984","male","P.O. Box 551, 3465 Sagittis Avenue","2016-11-08T07:45:41-08:00",955162444),("Quincy","Azalia","Hughes","iaculis.odio.Nam@eleifend.ca","611-773-1354","male","P.O. Box 978, 8774 A, Rd.","2016-06-01T18:31:39-07:00",949834895),("Stephen","Maya","Allison","Suspendisse@NullamnislMaecenas.edu","810-954-6728","female","197-1748 Dui Ave","2015-08-11T04:11:07-07:00",959548835),("Caldwell","Zelenia","Mclean","non.lorem.vitae@netus.co.uk","311-725-5285","female","Ap #493-2610 Ipsum St.","2015-08-25T04:39:57-07:00",939123787),("Hashim","Ulysses","Nolan","sed.pede@mauriselit.org","766-318-3804","male","274-3502 Felis Road","2015-05-26T17:57:30-07:00",917172607),("Dai","Iola","Acevedo","orci@mifringillami.com","797-599-7275","male","P.O. Box 900, 8384 Nascetur St.","2016-09-16T20:26:38-07:00",921514638),("Avye","Gray","Jordan","Duis.cursus.diam@eros.ca","663-639-5617","female","P.O. Box 910, 844 Eget St.","2015-09-27T20:31:04-07:00",970389507),("Keaton","Sean","Randolph","massa.Mauris.vestibulum@Etiamligula.com","806-344-6883","male","Ap #236-8908 Cursus Street","2016-10-11T16:31:17-07:00",948202558);
INSERT INTO Profiles (firstName,middleName,lastName,email,phone,gender,address,dob,SSN) VALUES ("Charity","Maite","Lancaster","purus.gravida@pede.edu","760-420-2799","male","336-4223 Leo. St.","2015-04-21T22:57:02-07:00",972145723),("Beau","Wynne","Crawford","lacus@dictumeleifendnunc.co.uk","170-676-9307","female","6385 Egestas. Rd.","2015-08-16T08:16:08-07:00",947933767),("Anastasia","Quentin","Blake","dolor.Fusce.feugiat@senectus.org","408-320-7930","male","P.O. Box 908, 1896 Sem Rd.","2015-02-08T00:05:18-08:00",991611526),("Lynn","Porter","Chase","sociosqu.ad@idantedictum.org","953-420-0432","male","4729 Risus Rd.","2015-05-18T19:39:25-07:00",957801972),("Madeson","Victor","Flores","sed@egestasnunc.edu","503-305-2638","male","P.O. Box 770, 7892 Molestie. St.","2016-01-17T21:13:32-08:00",969203946),("Ava","Macon","Logan","suscipit.est.ac@miac.co.uk","433-460-4545","male","593-2278 Orci St.","2015-08-06T12:49:54-07:00",987553838),("Brady","Steven","Boone","elementum@Phasellusdolor.net","391-525-4061","male","5037 Tincidunt Rd.","2015-07-23T23:43:00-07:00",909651349),("Amaya","Austin","Vasquez","consectetuer.rhoncus.Nullam@feugiatnon.co.uk","764-986-1534","female","532-6616 Mauris St.","2014-12-19T04:57:12-08:00",985279123),("Gisela","Raja","Mcgowan","aliquet.Proin.velit@dictum.com","917-842-0840","female","499-9314 Vitae Avenue","2015-01-17T04:42:13-08:00",959414990),("Ayanna","Mechelle","Freeman","non@sagittisfelisDonec.co.uk","488-699-5614","male","Ap #112-1798 Est St.","2016-10-20T10:07:16-07:00",957695583);
INSERT INTO Profiles (firstName,middleName,lastName,email,phone,gender,address,dob,SSN) VALUES ("Delilah","Dorothy","Rios","iaculis.lacus.pede@vitaeeratvel.ca","581-661-5907","male","Ap #278-8288 Orci. Rd.","2016-06-10T15:37:00-07:00",934957105),("Scarlett","Bryar","Fitzgerald","sapien.gravida.non@nisi.edu","890-922-1713","male","Ap #133-8046 Fames St.","2015-04-17T15:48:13-07:00",998722734),("Tamekah","Alec","Crawford","mi.fringilla@arcuSedet.co.uk","495-592-1877","female","P.O. Box 359, 9381 Velit Rd.","2015-12-22T15:09:14-08:00",973575115),("Ria","Emily","Malone","ante.bibendum.ullamcorper@ultriciesornare.ca","708-649-9680","male","P.O. Box 631, 2070 Tellus. Av.","2015-12-21T21:53:48-08:00",954112282),("Idola","Candace","Ruiz","mauris.Integer@ametloremsemper.net","786-811-5792","female","924-8998 Amet, Rd.","2016-06-12T10:46:09-07:00",925810918),("Maryam","Gretchen","Brewer","eros@ante.co.uk","823-836-2024","female","1494 Metus Ave","2016-06-24T10:34:18-07:00",929043571),("Madeson","Zephr","Dennis","lacinia@Phasellusliberomauris.org","655-636-8521","male","365-4722 Mauris Rd.","2016-03-24T21:46:52-07:00",980953229),("Dante","Mariko","Fleming","nibh.dolor.nonummy@rutrumeu.co.uk","859-238-6839","male","259-5935 Eget, Rd.","2016-05-22T11:26:21-07:00",903048094),("Abbot","Shelby","Mcdaniel","auctor.velit.eget@auctornuncnulla.com","470-358-8140","male","200-8694 Phasellus Av.","2015-02-17T17:28:18-08:00",996640998),("Noelani","Finn","Stevens","cubilia@ipsum.edu","662-480-6717","female","6478 Proin Road","2016-03-27T01:15:20-07:00",957798626);
INSERT INTO Profiles (firstName,middleName,lastName,email,phone,gender,address,dob,SSN) VALUES ("Geraldine","Alec","Johnson","hendrerit.Donec@Quisquelibero.edu","487-725-5810","male","P.O. Box 563, 959 Nunc Ave","2015-07-18T08:31:09-07:00",977956022),("Charde","Yolanda","Kidd","pellentesque.a@felispurusac.edu","396-699-0058","female","P.O. Box 743, 7717 Augue Rd.","2016-01-27T00:33:23-08:00",966380580),("Fuller","Deirdre","Rodriquez","id.mollis@Aeneaneuismodmauris.edu","868-166-4586","male","692-7696 Sed, Rd.","2016-05-24T14:38:35-07:00",906385166),("Armand","Davis","Maxwell","luctus@ridiculus.co.uk","154-802-9679","female","245-530 Per Avenue","2015-09-21T22:01:51-07:00",999718485),("Autumn","Kirestin","Walsh","ipsum.cursus@vulputateposuerevulputate.edu","428-284-5730","female","Ap #519-3032 Tristique St.","2016-03-09T17:52:41-08:00",906371170),("Iris","Nadine","Cervantes","lectus.ante@natoquepenatibus.com","264-638-1301","male","1528 Felis. Rd.","2015-12-21T05:45:23-08:00",908443821),("Abdul","Leo","Alvarez","Donec.consectetuer.mauris@aaliquetvel.edu","706-623-7706","male","Ap #822-4651 Turpis. Ave","2016-05-08T22:46:25-07:00",916305708),("Warren","Allegra","Fisher","euismod.est.arcu@nibhvulputatemauris.co.uk","437-775-0726","male","3100 Massa. Av.","2015-05-09T06:24:19-07:00",910606236),("Vielka","Lucian","Freeman","a.dui.Cras@nuncnulla.net","385-481-1696","male","P.O. Box 356, 8499 Sagittis. Street","2016-02-19T00:15:27-08:00",926676561),("Ignacia","Jermaine","Foreman","nunc@eteros.org","106-619-1244","female","591-245 Neque St.","2016-06-01T10:51:31-07:00",961859973);

LOCK TABLES Accounts WRITE;
INSERT INTO Accounts VALUES
    (0,1,170483472),(1,NULL,955961866),(2,NULL,558994585),
    (3,NULL,878387350),(4,NULL,993279341),(5,NULL,377655658),
    (6,NULL,807871269),(7,NULL,255586479),(8,NULL,442964430),
    (9,NULL,307966663),(10,NULL,716613537),(11,NULL,101523934),
    (12,NULL,194309555),(13,NULL,854806250),(14,NULL,291957302),
    (15,NULL,451299974),(16,NULL,639224828),(17,NULL,711133271),
    (18,NULL,963523048),(19,NULL,349404711),(20,NULL,407217073),
    (21,NULL,160374800),(22,NULL,866817522),(23,NULL,874941729),
    (24,NULL,967382619),(25,NULL,475277631),(26,NULL,716925833),
    (27,NULL,374593423),(28,NULL,126599540),(29,NULL,380422868),
    (30,NULL,432496887),(31,NULL,910808848),(32,NULL,741744741),
    (33,NULL,994771761),(34,NULL,558487602),(35,NULL,817335965),
    (36,NULL,273138490),(37,NULL,683695017),(38,NULL,787863591),
    (39,NULL,314555807),(40,NULL,372681261),(41,NULL,130678209),
    (42,NULL,450335767),(43,NULL,985529573),(44,NULL,172522626),
    (45,NULL,849419983),(46,NULL,664697809),(47,NULL,978472826),
    (48,NULL,166649482),(49,NULL,291234646),(50,NULL,570455442),
    (51,NULL,559898453),(52,NULL,648998251),(53,NULL,778483158),
    (54,NULL,557991734),(55,NULL,317855441),(56,NULL,800178857),
    (57,NULL,344175603),(58,NULL,240795610),(59,NULL,451907378);
UNLOCK TABLES;

insert into Accounts(profileID, SSN)
    values (1, 219543819);
insert into Accounts(profileID, SSN)
    values (2, 495829184);

update Profiles set password = 'pass123' where email = 'bob@email.com';

insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'18.81670345758', '43.49534570929', '72.59340301200', '2015-01-1 17:49:58');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'68.07842509209', '-2.611487384578', '-1.718678231475', '2015-01-2 0:29:0');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'81.34901497779', '-6.735610761538', '-4.791924228992', '2015-01-3 12:0:1');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'-7.674866020467', '56.99932712902', '-7.519409183759', '2015-01-4 6:22:59');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'-3.625516398173', '-1.904534346977', '-0.971902994967', '2015-01-5 4:34:56');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'71.89173481018', '-3.060326544364', '-3.169623863526', '2015-01-6 8:37:51');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'77.55615228961', '04.67397305164', '50.81363699426', '2015-01-7 17:30:43');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'58.24963780943', '-4.315659649566', '-7.647132382677', '2015-01-8 7:14:34');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'-0.114008986143', '35.14099667380', '93.39019132179', '2015-01-9 3:49:24');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(1,'-3.407048828362', '-8.964710148606', '04.65024839111', '2015-01-10 4:14:11');

insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'29.12884593619', '05.54620303345', '10.26158504305', '2015-01-1 10:30:56');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'93.29416894074', '70.96720021586', '-2.221300136940', '2015-01-2 21:37:40');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'30.48944939568', '-0.579090423238', '-2.451681715925', '2015-01-3 14:34:21');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'-4.289378709170', '93.12669398403', '26.26070361252', '2015-01-4 11:22:1');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'-2.923598314801', '-4.697408847071', '-2.916597975839', '2015-01-5 15:0:39');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'73.79524553560', '-5.112347958150', '-1.350133435868', '2015-01-6 23:30:15');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'05.37435537438', '27.98655644642', '-0.329797953137', '2015-01-7 12:49:49');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'09.07153146434', '56.16053903745', '-9.122570348857', '2015-01-8 7:0:21');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'77.89667580459', '42.74641835251', '87.61482880819', '2015-01-9 7:1:52');
insert into GPSData (profileID, latitude, longitude, altitude, dateAndTime) values(4,'19.74176768692', '-9.893508331170', '-6.934301289121', '2015-01-10 13:52:20');

