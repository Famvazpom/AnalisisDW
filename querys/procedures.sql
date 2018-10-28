CREATE PROC SEL_TODO_MONEDAS
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

CREATE PROC INSERTA_DIVISA
	@Pais  CHAR(50),
	@Cambio Money,
	@Fecha Date
AS 
	BEGIN
		DECLARE @ID_MONEDA INT;
		SELECT @ID_MONEDA = Id_Moneda FROM Monedas WHERE Pais = @Pais;
		IF (@ID_MONEDA IS NOT NULL)
			IF	(SELECT COUNT(*) FROM dbo.Divisas WHERE Fecha = @Fecha AND Moneda = @ID_MONEDA) > 0 
				UPDATE dbo.Divisas SET dbo.Divisas.TipoCambio = @Cambio WHERE Fecha = @Fecha AND Moneda = @ID_MONEDA;
			ELSE	
				INSERT INTO dbo.Divisas(Moneda,TipoCambio,Fecha) VALUES (
				    @ID_MONEDA, -- Moneda - int
				   @Cambio, -- TipoCambio - money
					@Fecha -- Fecha - date
					);
		ELSE
			PRINT N'MONEDA NO RECONOCIDA';
END;
GO
exit