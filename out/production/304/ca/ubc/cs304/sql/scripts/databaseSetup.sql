create table branch
(
    branch_id    integer not null primary key,
    branch_name  varchar2(20) not null,
    branch_addr  varchar2(50),
    branch_city  varchar2(20) not null,
    branch_phone integer
);

create table OnlineAudience
(
    Username char(20) not null primary key,
    Password char(20) not null,
);

create table InPersonAudience
(
    AudienceID   int      not null primary key,
    AudienceName char(20) not null,
);

create table Competition
(
    CompetitionName char(20) primary key,
    Time            char(20),
    Venue           char(20),
);

create table Volunteer
(
    VolunteerID      int primary key,
    Name             char(20),
    Responsibilities char(20),
);

create table MediaStreamingPlatform
(
    MediaPlatform  char(20) primary key,
    ViewCount      int,
    WatchDuruation int,
);

create table Sponsor
(
    SponsorName char(20) primary key,
    Address     char(20),
);

create table Country
(
    CountryName       char(20) primary key,
    CountryMedalCount int,
);

create table AthleteBelongs
(
    AthleteID   int primary key,
    Name        char(20) not null,
    Competition char(20) not null,
    MedalCount  int,
    TeamName    char(20),
    foreign key (TeamName) references Team
);

create table Team
(
    TeamName    char(20) primary key,
    Size        int      not null,
    Residency   char(20),
    CountryName char(20) not null UNIQUE,
    foreign key (CountryName) references Country
);

create table Represents
(
    TeamName    char(20) primary key,
    CountryName char(20) not null UNIQUE,
    foreign key (TeamName) references Team,
    foreign key (CountryName) references Country
);

create table Attends
(
    TicketNumber int primary key,
    AudienceID   int not null,
    foreign key (AudienceID) references InPersonAudience,
);

create table Ticket
(
    TicketNumber    int primary key,
    Seat            int      not null,
    CompetitionName char(20) not null,
    foreign key (CompetitionName) references Competition
);

create table TicketPrice
(
    CompetitionName char(20) not null primary key,
    Seat            int      not null primary key,
    Price           int      not null,
    foreign key (CompetitionName) references Competition
);

create table Watches
(
    Username      char(20) primary key,
    MediaPlatform char(20) primary key,
    StartTime     time not null,
    EndTime       time not null,
    foreign key (Username) references OnlineAudience,
    foreign key (MediaPlatform) references MediaStreamingPlatform
);

create table Streams
(
    CompetitionName char(20) primary key,
    MediaPlatform   char(20) primary key,
    foreign key (CompetitionName) references Competition,
    foreign key (MediaPlatform) references MediaStreamingPlatform
);

create table StreamPrice
(
    CompetitionName char(20) primary key,
    Price           int not null,
    foreign key (CompetitionName) references Competition,
);

create table Competes
(
    AthleteID       char(10) primary key,
    CompetitionName char(10) not null primary key,
    LockerRoom      char(10),
    Placement       char(10),
    foreign key (AthleteID) references AthleteBelongs,
    foreign key (CompetitionName) references Competition
);

create table Assists
(
    VolunteerID int primary key,
    AthleteID   int primary key,
    foreign key (VolunteerID) references Volunteer,
    foreign key (AthleteID) references AthleteBelongs
);

create table AthleteNeed
(
    AthleteID int primary key,
    Needs     char(50),
    foreign key (AthleteID) references AthleteBelongs
);

create table Funds
(
    SponsorName char(10) primary key,
    TeamName    char(10) primary key,
    Amount      int not null,
    foreign key (SponsorName) references Sponsor,
    foreign key (TeamName) references Team
);


INSERT INTO branch
VALUES (1, "ABC", "123 Charming Ave", "Vancouver", "6041234567");
INSERT INTO branch
VALUES (2, "DEF", "123 Coco Ave", "Vancouver", "6044567890");
