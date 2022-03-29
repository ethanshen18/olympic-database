drop table funds;
drop table athleteneed;
drop table assists;
drop table competes;
drop table streamprice;
drop table streams;
drop table watches;
drop table ticketprice;
drop table ticket;
drop table attends;
drop table represents;
drop table sponsor;
drop table mediastreamingplatform;
drop table volunteer;
drop table competition;
drop table inpersonaudience;
drop table onlineaudience;
drop table athletebelongs;
drop table team;
drop table country;

create table country
(
    countryname       char(50),
    countrymedalcount int,
    primary key (countryname)
);

insert into country
values ('Canada', 12);

insert into country
values ('USA', 5);

insert into country
values ('Germany', 3);

create table team
(
    teamname    char(50),
    teamsize    int      not null,
    residency   char(50),
    countryname char(50) not null unique,
    primary key (teamname),
    foreign key (countryname) references country on delete cascade
);

insert into team
values ('Team of Canada', 30, 'Building 1', 'Canada');

insert into team
values ('Team of USA', 45, 'Building 2', 'USA');

insert into team
values ('Team of Germany', 30, 'Building 3', 'Germany');

create table athletebelongs
(
    athleteid   int,
    name        char(50) not null,
    age         int not null,
    medalcount  int,
    teamname    char(50),
    primary key (athleteid),
    foreign key (teamname) references team on delete cascade
);

insert into athletebelongs
values (1, 'Jack McBain', 18, 2, 'Team of Canada');

insert into athletebelongs
values (2, 'Kent Johnson', 20, 0, 'Team of Canada');

insert into athletebelongs
values (3, 'Adam Tambellini', 19, 1, 'Team of Canada');

insert into athletebelongs
values (4, 'Landon Ferraro', 24, 3, 'Team of USA');

insert into athletebelongs
values (5, 'Adam Weal', 16, 0, 'Team of USA');

insert into athletebelongs
values (6, 'Alex Grant', 21, 0, 'Team of USA');

insert into athletebelongs
values (7, 'Jack Robinson', 25, 1, 'Team of USA');

create table onlineaudience
(
    username char(50) not null,
    password char(50) not null,
    primary key (username)
);

insert into onlineaudience
values ('User 1', 'Em52kqvj');

create table inpersonaudience
(
    audienceid   int      not null,
    audiencename char(50) not null,
    primary key (audienceid)
);

insert into inpersonaudience
values (1, 'John Smith');

create table competition
(
    competitionname char(50),
    competitiontime char(50),
    venue           char(50),
    primary key (competitionname)
);

insert into competition
values ('Skating', '2022-02-16 15:30:00', 'Arena 1');

insert into competition
values ('Hockey', '2022-02-16 12:00:00', 'Arena 5');

insert into competition
values ('Skiing', '2022-02-18 18:30:00', 'Arena 3');

create table volunteer
(
    volunteerid      int,
    name             char(50),
    responsibilities char(50),
    primary key (volunteerid)
);

insert into volunteer
values (1, 'John Doe', 'Guide atheletes to locker rooms');

create table mediastreamingplatform
(
    mediaplatform  char(50),
    viewcount      int,
    watchduruation int,
    primary key (mediaplatform)
);

insert into mediastreamingplatform
values ('BBC', 50000, 2000);

create table sponsor
(
    sponsorname char(50),
    address     char(50),
    primary key (sponsorname)
);

insert into sponsor
values ('Nike', 'New York');

create table represents
(
    teamname    char(50),
    countryname char(50) not null unique,
    primary key (teamname),
    foreign key (teamname) references team on delete cascade,
    foreign key (countryname) references country on delete cascade
);

insert into represents
values ('Team of Canada', 'Canada');

insert into represents
values ('Team of USA', 'USA');

insert into represents
values ('Team of Germany', 'Germany');

create table attends
(
    ticketnumber int,
    audienceid   int not null,
    primary key (ticketnumber),
    foreign key (audienceid) references inpersonaudience on delete cascade
);

insert into attends
values (1, 1);

create table ticket
(
    ticketnumber    int,
    seat            int      not null,
    competitionname char(50) not null,
    primary key (ticketnumber),
    foreign key (competitionname) references competition on delete cascade
);

insert into ticket
values (1, 50, 'Skating');

create table ticketprice
(
    competitionname char(50) not null,
    seat            int      not null,
    price           int      not null,
    primary key (competitionname, seat),
    foreign key (competitionname) references competition on delete cascade
);

insert into ticketprice
values ('Skating', 50, 200);

create table watches
(
    username      char(50),
    mediaplatform char(50),
    starttime     char(50) not null,
    endtime       char(50) not null,
    primary key (username, mediaplatform),
    foreign key (username) references onlineaudience on delete cascade,
    foreign key (mediaplatform) references mediastreamingplatform on delete cascade
);

insert into watches
values ('User 1', 'BBC', '2022-02-16 15:30:00', '2022-02-16 18:30:00');

create table streams
(
    competitionname char(50),
    mediaplatform   char(50),
    primary key (competitionname, mediaplatform),
    foreign key (competitionname) references competition on delete cascade,
    foreign key (mediaplatform) references mediastreamingplatform on delete cascade
);

insert into streams
values ('Skating', 'BBC');

create table streamprice
(
    competitionname char(50),
    price           int not null,
    primary key (competitionname),
    foreign key (competitionname) references competition on delete cascade
);

insert into streamprice
values ('Skating', 50000);

create table competes
(
    athleteid       int,
    competitionname char(50) not null,
    lockerroom      int,
    placement       int,
    primary key (athleteid, competitionname),
    foreign key (athleteid) references athletebelongs on delete cascade,
    foreign key (competitionname) references competition on delete cascade
);

insert into competes
values (1, 'Skating', 5, 3);

insert into competes
values (1, 'Hockey', 8, 1);

insert into competes
values (1, 'Skiing', 2, 9);

insert into competes
values (2, 'Skating', 7, 5);

insert into competes
values (2, 'Hockey', 14, 8);

insert into competes
values (3, 'Skating', 16, 4);

insert into competes
values (4, 'Skating', 4, 12);

insert into competes
values (5, 'Skating', 7, 2);

insert into competes
values (6, 'Skating', 12, 6);

insert into competes
values (7, 'Skating', 10, 7);

create table assists
(
    volunteerid int,
    athleteid   int,
    primary key (volunteerid, athleteid),
    foreign key (volunteerid) references volunteer on delete cascade,
    foreign key (athleteid) references athletebelongs on delete cascade
);

insert into assists
values (1, 1);

create table athleteneed
(
    athleteid int,
    needs     char(50),
    primary key (athleteid),
    foreign key (athleteid) references athletebelongs on delete cascade
);

insert into athleteneed
values (1, 'Extra towels');

create table funds
(
    sponsorname char(50),
    teamname    char(50),
    amount      int not null,
    primary key (sponsorname, teamname),
    foreign key (sponsorname) references sponsor on delete cascade,
    foreign key (teamname) references team on delete cascade
);

insert into funds
values ('Nike', 'Team of Canada', 200000);