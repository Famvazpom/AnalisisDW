/*CREATE PROC SEL_TODO_MONEDAS
AS
BEGIN
	SELECT * FROM Monedas;
END;
GO


CREATE PROC INSERTA_MONEDA 
@Moneda CHAR(50),
@Pais CHAR(50)
AS
BEGIN
	INSERT INTO dbo.Monedas
	(
	    Moneda,
	    Pais
	)
	VALUES
	(   @Moneda, -- Moneda - char(50)
	    @Pais  -- Pais - char(60)
	    );
END;
GO
*/
/*
CREATE PROC INSERTA_DIVISA
	@Moneda  CHAR(50),
	@Cambio Money,
	@Fecha Date
AS 
	BEGIN
		DECLARE @ID_MONEDA INT;
		SELECT @ID_MONEDA = Id_Moneda FROM Monedas WHERE Moneda = @Moneda;
		INSERT INTO dbo.Divisa_Actual VALUES ( @ID_MONEDA,@Cambio);
		SELECT * FROM Divisa_Actual;
END;
*/

USE Divisas;
EXECUTE	INSERTA_DIVISA @Moneda= 'PESO',@Cambio = 22.5,@Fecha = "2018-06-19";
