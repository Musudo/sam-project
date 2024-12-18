import React, {useRef, useState} from "react";
import {IActivity} from "../../../models/IActivity";
import {
	Box,
	Button,
	ButtonGroup,
	ClickAwayListener,
	Container,
	FormControl,
	FormControlLabel,
	Grid,
	Grow,
	IconButton,
	MenuItem,
	MenuList,
	Paper,
	Popper,
	Switch,
	useMediaQuery
} from "@mui/material";
import {ActivitiesColumn} from "./ActivitiesColumn";
import {IInstitution} from "../../../models/IInstitution";
import {ActivitySpeedDial} from "./ActivitySpeedDial";
import {fetchDataReactQuery} from "../../../utils/HttpRequestUtil";
import InstitutionSearchBar from "./InstitutionSearchBar";
import ArchivedActivityCard from "./ArchivedActivityCard";
import FilterListIcon from '@mui/icons-material/FilterList';
import ArrowDropDownIcon from '@mui/icons-material/ArrowDropDown';
import LoadingComponent from "../../LoadingComponent";
import ActivitiesSwipeableMobile from "./ActivitiesSwipeableMobile";
import {useTranslation} from "react-i18next";
import {ArchivedActivitiesYearPicker} from "./ArchivedActivitiesYearPicker";
import {useQuery} from "@tanstack/react-query";
import {ActivityColumnsEnum, SpeedDialDirectionsEnum} from "../../../enums/ComponentPropsEnums";

export function ActivitiesOverview() {
	const [institution, setInstitution] = useState<IInstitution | null>(null);
	const [isArchived, setIsArchived] = useState(false);
	const isMobile = useMediaQuery('(max-width: 600px)');
	const {t} = useTranslation();

	/* archive button configuration >> */
	const [archivedYear, setArchivedYear] = useState<Date | null>(new Date());
	const handleArchivedYearChange = (newValue: Date | null) => setArchivedYear(newValue);
	/* << archive button configuration */

	/* sort button configuration >> */
	const options = ['Earliest first', 'Latest first'];
	const [open, setOpen] = useState(false);
	const anchorRef = useRef<HTMLDivElement>(null);
	const [selectedIndex, setSelectedIndex] = useState<number>(0);

	const handleSortButtonClick = (event: any, index: number) => {
		if (selectedIndex !== index) {
			setSelectedIndex(index);
			setOpen(false);
			sortActivitiesByDate();
		}
	};

	const handleSortButtonOpen = () => setOpen((prevOpen: boolean) => !prevOpen);

	const handleSortButtonClose = (event: any) => {
		if (anchorRef.current && anchorRef.current.contains(event.target)) return;

		setOpen(false);
	};

	const handleSortButtonClickMobile = (event: any) => {
		setSelectedIndex(selectedIndex === 1 ? 0 : 1);
		sortActivitiesByDate();
	}
	/* << sort button configuration */

	const {data: activitiesToday, status: statusOfActivitiesToday} = useQuery<IActivity[]>(
		['activitiesToday', institution],
		() => {
			if (institution) {
				return fetchDataReactQuery(`/activities/today/institution-info/${institution.guid}`);
			} else {
				return fetchDataReactQuery('/activities/today');
			}
		}
	);

	const {data: activitiesNextSevenDays, status: statusOfActivitiesNextSevenDays} = useQuery<IActivity[]>(
		['activitiesNextSevenDays', institution],
		() => {
			if (institution) {
				return fetchDataReactQuery(`/activities/next-seven-days/institution-info/${institution.guid}`);
			} else {
				return fetchDataReactQuery('/activities/next-seven-days');
			}
		}
	);

	const {data: activitiesNextThirtyDays, status: statusOfActivitiesNextThirtyDays} = useQuery<IActivity[]>(
		['activitiesNextThirtyDays', institution],
		() => {
			if (institution) {
				return fetchDataReactQuery(`/activities/next-thirty-days/institution-info/${institution.guid}`);
			} else {
				return fetchDataReactQuery('/activities/next-thirty-days');
			}
		}
	);

	const {data: expiredActivities, status: statusOfExpiredActivities} = useQuery<IActivity[]>(
		['expiredActivities', archivedYear],
		() => {
			let year = `${archivedYear?.getFullYear().toString()}-01-01 00:00`;
			return fetchDataReactQuery(`/activities/expired/${year}`)
		}
	);

	const sortActivitiesByDate = () => {
		if (!isArchived) {
			activitiesToday && activitiesToday.sort().reverse();
			activitiesNextSevenDays && activitiesNextSevenDays.sort().reverse();
			activitiesNextThirtyDays && activitiesNextThirtyDays.sort().reverse();
		} else {
			expiredActivities && expiredActivities.sort().reverse();
		}
	}

	const handleArchivedActivitiesSwitchChange = () => setIsArchived(current => !current);

	const loadingStatuses = [
		statusOfActivitiesToday,
		statusOfExpiredActivities,
		statusOfActivitiesNextSevenDays,
		statusOfActivitiesNextThirtyDays
	];

	if (loadingStatuses.some(status => status === 'loading')) return <LoadingComponent/>;

	return (
		<Container sx={{
			flexGrow: 1,
			overflow: 'auto',
			py: 2
		}} maxWidth='lg'>
			{/*show page functionality and activities for desktop view*/}
			{!isMobile ? (
				<>
					<Grid item sx={{display: 'flex', justifyContent: 'space-between', marginBottom: "1em"}}>
						<Box display="flex" flexDirection="row" width="90vh">
							<FormControl sx={{width: 250}}>
								<InstitutionSearchBar setInstitution={setInstitution} institution={institution}
													  setContacts={null} setValue={null}/>
							</FormControl>
							<FormControl component="fieldset" variant="standard" sx={{ml: 2}}>
								<FormControlLabel
									sx={{width: 240}}
									control={<Switch checked={isArchived} name="archive"
													 onChange={handleArchivedActivitiesSwitchChange}/>}
									label={t('Activities overview page.Show archived activities')}/>
							</FormControl>
							<Box sx={{width: 300, ml: 1}}>
								<ButtonGroup variant="text" ref={anchorRef} aria-label="sort button">
									<Button aria-controls='button-menu'
											aria-expanded='true'
											aria-label="sort button"
											aria-haspopup="menu"
											startIcon={<FilterListIcon/>}
											endIcon={<ArrowDropDownIcon/>}
											onClick={handleSortButtonOpen}>
										{t(`Activities overview page.${options[selectedIndex]}`)}
									</Button>
								</ButtonGroup>
								<Popper
									sx={{zIndex: 1}}
									open={open}
									anchorEl={anchorRef.current}
									role={undefined}
									transition
									disablePortal>
									{({TransitionProps, placement}) => (
										<Grow
											{...TransitionProps}
											style={{
												transformOrigin:
													placement === 'bottom' ? 'center top' : 'center bottom',
											}}>
											<Paper>
												<ClickAwayListener onClickAway={handleSortButtonClose}>
													<MenuList id="button-menu" autoFocusItem>
														{options.map((option: string, index: number) => (
															<MenuItem
																key={option}
																selected={index === selectedIndex}
																onClick={(event) => handleSortButtonClick(event, index)}>
																{t(`Activities overview page.${option}`)}
															</MenuItem>
														))}
													</MenuList>
												</ClickAwayListener>
											</Paper>
										</Grow>
									)}
								</Popper>
							</Box>
						</Box>
						<Box mt={7}>
							<ActivitySpeedDial direction={SpeedDialDirectionsEnum.Left}/>
						</Box>
					</Grid>
					<Paper elevation={0} sx={{py: 2, px: 2, bgcolor: "#eaeaee"}}>
						{isArchived ? (
							<Grid container spacing={2}>
								<Grid item xs={12}>
									<ArchivedActivitiesYearPicker archivedYear={archivedYear}
																  handleArchivedYearChange={handleArchivedYearChange}/>
								</Grid>
								<Grid item xs={12} display="flex" flexWrap="wrap" width="100%" gap={2}>
									{expiredActivities && expiredActivities.map(activity =>
										<ArchivedActivityCard activity={activity}/>
									)}
								</Grid>
							</Grid>
						) : (
							<Grid container
								  display="flex"
								  justifyContent="space-around"
								  spacing={{xs: 1, sm: 2, md: 3}}>
								{<ActivitiesColumn activities={activitiesToday ?? []}
												   columnName={t('Activities overview page.Today') as ActivityColumnsEnum.Today}/>}
								{<ActivitiesColumn activities={activitiesNextSevenDays ?? []}
												   columnName={t('Activities overview page.Next 7 days') as ActivityColumnsEnum.Next_7_Days}/>}
								{<ActivitiesColumn activities={activitiesNextThirtyDays ?? []}
												   columnName={t('Activities overview page.Next 30 days') as ActivityColumnsEnum.Next_30_Days}/>}
							</Grid>
						)}
					</Paper>
				</>

			) : (
				<>
					{/*show page functionality and activities for mobile view*/}
					<Box display="flex" flexDirection="row" mb={2}>
						<FormControl fullWidth>
							<InstitutionSearchBar setInstitution={setInstitution} institution={institution}
												  setContacts={null} setValue={null}/>
						</FormControl>
						<FormControl component="fieldset" variant="standard" sx={{ml: 2}}>
							<FormControlLabel
								control={<Switch checked={isArchived} name="archive"
												 onChange={handleArchivedActivitiesSwitchChange}/>}
								label=""/>
						</FormControl>
						<IconButton aria-label="sort" color="primary"
									onClick={(event) => handleSortButtonClickMobile(event)}>
							<FilterListIcon/>
						</IconButton>
					</Box>
					{isArchived ? (
						<Paper elevation={0} sx={{py: 2, px: 2, bgcolor: "#eaeaee"}}>
							<Grid container spacing={2}>
								<Grid item xs={12}>
									<ArchivedActivitiesYearPicker archivedYear={archivedYear}
																  handleArchivedYearChange={handleArchivedYearChange}/>
								</Grid>
								<Grid item display="flex" flexWrap="wrap" width="100%" gap={1}>
									{expiredActivities && expiredActivities.map(activity =>
										<ArchivedActivityCard activity={activity}/>)}
								</Grid>
							</Grid>
						</Paper>
					) : (
						<ActivitiesSwipeableMobile
							activitiesToday={activitiesToday ?? []}
							activitiesNextSevenDays={activitiesNextSevenDays ?? []}
							activitiesNextThirtyDays={activitiesNextThirtyDays ?? []}/>
					)}
				</>
			)}

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
		</Container>
	);
}