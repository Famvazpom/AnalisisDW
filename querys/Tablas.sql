USE Divisas;
CREATE Table Monedas( Id_Moneda int primary key identity, Moneda char(50) not null unique, Pais char(60) not null unique);
Create Table Divisas( Id_Divisa int primary key identity, Moneda int foreign key references dbo.Monedas(Id_Moneda) not null, TipoCambio money not null, Fecha Date not null );