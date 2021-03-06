USE [CRAFTBEER]
GO
/****** Object:  StoredProcedure [dbo].[get_time_dialog_full]    Script Date: 7/1/2022 4:44:52 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[get_time_dialog_full](
@timeStart datetime,
@timeEnd datetime = null,
@DeviceID int = -1,
@unit varchar(50) = 'empty',
@locationID int = -1,
@objectID int = -1
)
as
begin
if(@timeEnd = null)
begin 
set @timeEnd = GETDATE();
end
select * from Time_Analog 
inner join 
(select Device.DeviceID as DeviceID,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor from Device 
	inner join Object on Device.Dev_ObjID = Object.ObjectID
	inner join Location on Object.Obj_LocID = Location.LocationID
	inner join Threshold on Device.DeviceID = Threshold.DeviceID
	where ((@DeviceID!=-1 and Device.DeviceID = @DeviceID) or (@DeviceID=-1))
	and ((@locationID!=-1 and Object.Obj_LocID = @locationID) or (@locationID=-1))
	and ((@objectID!=-1 and Device.Dev_ObjID = @objectID) or (@objectID=-1))
	and ((@unit!='empty' and Device.Dev_Unit = @unit) or (@unit='empty'))
	) as DevFullInfo
	on Time_Analog.DeviceID = DevFullInfo.DeviceID
	where (Time_Analog.Recordtime BETWEEN @timeStart AND @timeEnd)
	order by DevFullInfo.DeviceID, Recordtime;
end

/*EXEC get_time_dialog_full @timeStart = '2022-03-01',@timeEnd =  '2022-04-29 09:04:42';
GO
select cast(cast(my_date_field as float) as int)*/

GO
/****** Object:  StoredProcedure [dbo].[get_time_dialog_per_day_full]    Script Date: 7/1/2022 4:44:52 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[get_time_dialog_per_day_full](
@timeStart datetime,
@timeEnd datetime = null,
@DeviceID int = -1,
@unit varchar(50) = 'empty',
@locationID int = -1,
@objectID int = -1
)
as
begin
if(@timeEnd = null)
begin 
set @timeEnd = GETDATE();
end
select 
DevFullInfo.DeviceID
,AVG(Value) as Value,
min(Recordtime) as Recordtime,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
from Time_Analog 
inner join 
(select Device.DeviceID as DeviceID,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor from Device 
	inner join Object on Device.Dev_ObjID = Object.ObjectID
	inner join Location on Object.Obj_LocID = Location.LocationID
	inner join Threshold on Device.DeviceID = Threshold.DeviceID
	where ((@DeviceID!=-1 and Device.DeviceID = @DeviceID) or (@DeviceID=-1))
	and ((@locationID!=-1 and Object.Obj_LocID = @locationID) or (@locationID=-1))
	and ((@objectID!=-1 and Device.Dev_ObjID = @objectID) or (@objectID=-1))
	and ((@unit!='empty' and Device.Dev_Unit = @unit) or (@unit='empty'))
	) as DevFullInfo
	on Time_Analog.DeviceID = DevFullInfo.DeviceID
	where (Time_Analog.Recordtime BETWEEN @timeStart AND @timeEnd)
	group by DevFullInfo.DeviceID, 
	datepart(month, Recordtime),
    datepart(day, Recordtime),
	datepart(hour, Recordtime),
    floor(datepart(minute, Recordtime)/30)*30,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
order by DevFullInfo.DeviceID,Recordtime
end

/*EXEC get_time_dialog_per_hour_full @timeStart = '2022-03-01',@timeEnd =  '2022-04-29 09:04:42';
GO
select cast(cast(my_date_field as float) as int)*/

GO
/****** Object:  StoredProcedure [dbo].[get_time_dialog_per_hour_full]    Script Date: 7/1/2022 4:44:52 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[get_time_dialog_per_hour_full](
@timeStart datetime,
@timeEnd datetime = null,
@DeviceID int = -1,
@unit varchar(50) = 'empty',
@locationID int = -1,
@objectID int = -1
)
as
begin
if(@timeEnd = null)
begin 
set @timeEnd = GETDATE();
end
select 
DevFullInfo.DeviceID
,AVG(Value) as Value,
min(Recordtime) as Recordtime,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
from Time_Analog 
inner join 
(select Device.DeviceID as DeviceID,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor from Device 
	inner join Object on Device.Dev_ObjID = Object.ObjectID
	inner join Location on Object.Obj_LocID = Location.LocationID
	inner join Threshold on Device.DeviceID = Threshold.DeviceID
	where ((@DeviceID!=-1 and Device.DeviceID = @DeviceID) or (@DeviceID=-1))
	and ((@locationID!=-1 and Object.Obj_LocID = @locationID) or (@locationID=-1))
	and ((@objectID!=-1 and Device.Dev_ObjID = @objectID) or (@objectID=-1))
	and ((@unit!='empty' and Device.Dev_Unit = @unit) or (@unit='empty'))
	) as DevFullInfo
	on Time_Analog.DeviceID = DevFullInfo.DeviceID
	where (Time_Analog.Recordtime BETWEEN @timeStart AND @timeEnd)
	group by DevFullInfo.DeviceID, 
	datepart(month, Recordtime),
    datepart(day, Recordtime),
	datepart(hour, Recordtime),
    datepart(minute, Recordtime),
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
order by DevFullInfo.DeviceID,Recordtime
end

/*EXEC get_time_dialog_per_hour_full @timeStart = '2022-03-01',@timeEnd =  '2022-04-29 09:04:42';
GO
select cast(cast(my_date_field as float) as int)*/

GO
/****** Object:  StoredProcedure [dbo].[get_time_dialog_per_month_full]    Script Date: 7/1/2022 4:44:52 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[get_time_dialog_per_month_full](
@timeStart datetime,
@timeEnd datetime = null,
@DeviceID int = -1,
@unit varchar(50) = 'empty',
@locationID int = -1,
@objectID int = -1
)
as
begin
if(@timeEnd = null)
begin 
set @timeEnd = GETDATE();
end
select 
DevFullInfo.DeviceID
,AVG(Value) as Value,
min(Recordtime) as Recordtime,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
from Time_Analog 
inner join 
(select Device.DeviceID as DeviceID,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor from Device 
	inner join Object on Device.Dev_ObjID = Object.ObjectID
	inner join Location on Object.Obj_LocID = Location.LocationID
	inner join Threshold on Device.DeviceID = Threshold.DeviceID
	where ((@DeviceID!=-1 and Device.DeviceID = @DeviceID) or (@DeviceID=-1))
	and ((@locationID!=-1 and Object.Obj_LocID = @locationID) or (@locationID=-1))
	and ((@objectID!=-1 and Device.Dev_ObjID = @objectID) or (@objectID=-1))
	and ((@unit!='empty' and Device.Dev_Unit = @unit) or (@unit='empty'))
	) as DevFullInfo
	on Time_Analog.DeviceID = DevFullInfo.DeviceID
	where (Time_Analog.Recordtime BETWEEN @timeStart AND @timeEnd)
	group by DevFullInfo.DeviceID, 
	datepart(month, Recordtime),
    datepart(day, Recordtime),
    floor(datepart(hour, Recordtime)/12)*12,Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
order by DevFullInfo.DeviceID,Recordtime
end

/*EXEC get_time_dialog_per_week_full @timeStart = '2022-03-01',@timeEnd =  '2022-03-30';
GO
select cast(cast(my_date_field as float) as int)*/

GO
/****** Object:  StoredProcedure [dbo].[get_time_dialog_per_week_full]    Script Date: 7/1/2022 4:44:52 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[get_time_dialog_per_week_full](
@timeStart datetime,
@timeEnd datetime = null,
@DeviceID int = -1,
@unit varchar(50) = 'empty',
@locationID int = -1,
@objectID int = -1
)
as
begin
if(@timeEnd = null)
begin 
set @timeEnd = GETDATE();
end
select 
DevFullInfo.DeviceID
,AVG(Value) as Value,
min(Recordtime) as Recordtime,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
from Time_Analog 
inner join 
(select Device.DeviceID as DeviceID,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor from Device 
	inner join Object on Device.Dev_ObjID = Object.ObjectID
	inner join Location on Object.Obj_LocID = Location.LocationID
	inner join Threshold on Device.DeviceID = Threshold.DeviceID
	where ((@DeviceID!=-1 and Device.DeviceID = @DeviceID) or (@DeviceID=-1))
	and ((@locationID!=-1 and Object.Obj_LocID = @locationID) or (@locationID=-1))
	and ((@objectID!=-1 and Device.Dev_ObjID = @objectID) or (@objectID=-1))
	and ((@unit!='empty' and Device.Dev_Unit = @unit) or (@unit='empty'))
	) as DevFullInfo
	on Time_Analog.DeviceID = DevFullInfo.DeviceID
	where (Time_Analog.Recordtime BETWEEN @timeStart AND @timeEnd)
	group by DevFullInfo.DeviceID, 
	datepart(month, Recordtime),
    datepart(day, Recordtime),
    floor(datepart(hour, Recordtime)/6)*6,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
order by DevFullInfo.DeviceID,Recordtime
end

/*EXEC get_time_dialog_per_day_full @timeStart = '2022-03-01',@timeEnd =  '2022-03-02';
GO
select cast(cast(my_date_field as float) as int)*/

GO
/****** Object:  StoredProcedure [dbo].[get_time_dialog_per_year_full]    Script Date: 7/1/2022 4:44:52 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[get_time_dialog_per_year_full](
@timeStart datetime,
@timeEnd datetime = null,
@DeviceID int = -1,
@unit varchar(50) = 'empty',
@locationID int = -1,
@objectID int = -1
)
as
begin
if(@timeEnd = null)
begin 
set @timeEnd = GETDATE();
end
select 
DevFullInfo.DeviceID
,AVG(Value) as Value,
min(Recordtime) as Recordtime,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
from Time_Analog 
inner join 
(select Device.DeviceID as DeviceID,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor from Device 
	inner join Object on Device.Dev_ObjID = Object.ObjectID
	inner join Location on Object.Obj_LocID = Location.LocationID
	inner join Threshold on Device.DeviceID = Threshold.DeviceID
	where ((@DeviceID!=-1 and Device.DeviceID = @DeviceID) or (@DeviceID=-1))
	and ((@locationID!=-1 and Object.Obj_LocID = @locationID) or (@locationID=-1))
	and ((@objectID!=-1 and Device.Dev_ObjID = @objectID) or (@objectID=-1))
	and ((@unit!='empty' and Device.Dev_Unit = @unit) or (@unit='empty'))
	) as DevFullInfo
	on Time_Analog.DeviceID = DevFullInfo.DeviceID
	where (Time_Analog.Recordtime BETWEEN @timeStart AND @timeEnd)
	group by DevFullInfo.DeviceID, 
	datepart(month, Recordtime),
    floor(datepart(day, Recordtime)/8)*8,
	Dev_Name,
	Dev_Des,
	Obj_Name,
	Dev_Unit,
	t_ceil,
	t_floor
order by DevFullInfo.DeviceID,Recordtime
end

/*EXEC get_time_dialog_per_month_full @timeStart = '2022-03-01',@timeEnd =  '2022-03-30';
GO
select cast(cast(my_date_field as float) as int)*/

GO
