import React, {useEffect, useState} from "react";
import {
	Box, CircularProgress, Container, createTheme, CssBaseline, Divider, Grid, IconButton, List, Paper,
	styled, Toolbar, useMediaQuery
} from "@mui/material";
import {Total} from "./Total";
import {ActivitySpeedDial} from "../activities/overview/ActivitySpeedDial";
import {Summary} from "./Summary";
import {NextActivities} from "./NextActivities";
import {HotTasks} from "./HotTasks";
import {fetchData} from "../../utils/HttpRequestUtil";
import {IActivity} from "../../models/IActivity";
import {ITask} from "../../models/ITask";
import {SpeedDialDirectionsEnum, TaskTypesEnum} from "../../enums/ComponentPropsEnums";
import {AppBarProps as MuiAppBarProps} from "@mui/material/AppBar/AppBar";
import MuiAppBar from "@mui/material/AppBar";
import MuiDrawer from "@mui/material/Drawer";
import ChevronLeftIcon from "@mui/icons-material/ChevronLeft";
import {mainListItems, secondaryListItems} from "./listItems";
import {CustomDrawer} from "./CustomDrawer";


export function Dashboard() {
	const [activities, setActivities] = useState<IActivity[]>([]);
	const [phone, setPhone] = useState<IActivity[]>([]);
	const [physical, setPhysical] = useState<IActivity[]>([]);
	const [remote, setRemote] = useState<IActivity[]>([]);
	const [tasks, setTasks] = useState<ITask[]>([]);
	const [isLoading, setIsLoading] = useState(true);
	const isMobile = useMediaQuery('(max-width: 600px)');


	useEffect(() => {
		fetchData("/activities")
			.then((response) => {
				if (response?.data.length > 0) {
					setActivities(response?.data);
					setPhone(activities.filter(activity => activity.type === 'Phone conversation'));
					setPhysical(activities.filter(activity => activity.type === 'Physical meeting'));
					setRemote(activities.filter(activity => activity.type === 'Online'));
				}
				setIsLoading(false);
			})
			.catch(() => console.error("Failed to fetch activities"));
	}, []);

	useEffect(() => {
		fetchData("/tasks")
			.then((response) => {
				if (response?.data.length > 0) {
					setTasks(response?.data);
				}
			})
			.catch(() => console.error("Failed to fetch tasks"));
	}, []);

	if (isLoading) {
		return (
			<Grid
				container
				spacing={0}
				direction="column"
				alignItems="center"
				justifyContent="center"
				style={{minHeight: '80vh'}}
			>
				<CircularProgress/>
			</Grid>
		);
	}

	return (
		<>
			<Box sx={{display: 'flex'}}>
				{/*<CustomDrawer/>*/}
				<Container sx={{
					flexGrow: 1,
					overflow: 'auto',
				}} maxWidth='lg'>

					{/*show speed dial for desktop view*/}
					{!isMobile && (
						<Grid container item spacing={1} sx={{height: 75}}>
							<ActivitySpeedDial direction={SpeedDialDirectionsEnum.Left}/>
						</Grid>
					)}

					<Grid container spacing={3}>
						{/*Paper 1: Summary*/}
						<Grid item xs={12} md={8} lg={9}>
							<Paper
								sx={{
									p: 2,
									display: 'flex',
									flexDirection: 'column',
									minHeight: 400,
								}}>
								<Summary phone={phone} physical={physical} remote={remote}/>
							</Paper>
						</Grid>
						{/*Paper 2: Total*/}
						<Grid item xs={12} md={4} lg={3}>
							<Paper
								sx={{
									p: 2,
									display: 'flex',
									flexDirection: 'column',
									minHeight: 400,
								}}>
								<Total totalActivities={activities.length > 0 ? activities.length : 0}
									   totalTasks={tasks.length}/>
							</Paper>
						</Grid>
						{/*Paper 3: NextActivities*/}
						<Grid item xs={12} md={6} lg={6}>
							<Paper sx={{p: 2, display: 'flex', flexDirection: 'column', minHeight: 400}}>
								<NextActivities activities={activities}/>
							</Paper>
						</Grid>
						{/*Paper 4: New HotTasks*/}
						<Grid item xs={12} md={3} lg={3}>
							<Paper sx={{p: 2, display: 'flex', flexDirection: 'column', minHeight: 400}}>
								<HotTasks tasks={tasks} type={TaskTypesEnum.Newest} activities={activities}/>
							</Paper>
						</Grid>
						{/*Paper 5: Old HotTasks*/}
						<Grid item xs={12} md={3} lg={3}>
							<Paper sx={{p: 2, display: 'flex', flexDirection: 'column', minHeight: 400}}>
								<HotTasks tasks={tasks} type={TaskTypesEnum.Oldest} activities={activities}/>
							</Paper>
						</Grid>
					</Grid>
				</Container>
			</Box>

			{/*show speed dial for mobile view*/}
			{isMobile && (
				<Grid item xs={12}>
					<Box
						position="fixed"
						bottom={0}
						left={0}
						width="100%"
						p={2}
						// bgcolor="primary.main"
						// color="primary.contrastText"
						textAlign="center"
					>
						<ActivitySpeedDial direction={SpeedDialDirectionsEnum.Up}/>
					</Box>
				</Grid>
			)}
		</>
	);
}