import React, {useState} from "react";
import {IActivity} from "../../../models/IActivity";
import {useParams} from "react-router-dom";
import {
	Box,
	Button,
	Chip,
	CircularProgress,
	Container,
	Grid,
	IconButton,
	Paper,
	Tab,
	Tabs,
	Typography,
	useMediaQuery
} from "@mui/material";
import dayjs from "dayjs";
import PeopleIcon from '@mui/icons-material/People';
import ConnectWithoutContactIcon from '@mui/icons-material/ConnectWithoutContact';
import LocalPhoneIcon from '@mui/icons-material/LocalPhone';
import {ActivityTasks} from "./ActivityTasks";
import {ActivityAside} from "./ActivityAside";
import {ParticipantsList} from "./participants/ParticipantsList";
import {ExternalParticipantsList} from "./participants/ExternalParticipantsList";
import {ActivityParticipantsDialog} from "./participants/ActivityParticipantsDialog";
import {fetchDataReactQuery, postDataReactQuery} from "../../../utils/HttpRequestUtil";
import {ActivityCancelDialog} from "./ActivityCancelDialog";
import {ActivityVoiceMemo} from "./ActivityVoiceMemo";
import {ActivityReview} from "./ActivityReview";
import {ActivityExternalNote} from "./notes/ActivityExternalNote";
import {ActivityInternalNote} from "./notes/ActivityInternalNote";
import EmailIcon from '@mui/icons-material/Email';
import DeleteIcon from '@mui/icons-material/Delete';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import {allyProps} from "../../../props/MUIElementProps";
import {useMutation, useQuery, useQueryClient} from "@tanstack/react-query";
import {ErrorComponent} from "../../ErrorComponent";
import {ActivityTypesEnum} from "../../../enums/ActivityTypesEnum";
import {ParticipantTypesEnum} from "../../../enums/ComponentPropsEnums";
import {ErrorTypesEnum} from "../../../enums/ErrorTypesEnum";
import LoadingComponent from "../../LoadingComponent";

dayjs.extend(utc);
dayjs.extend(timezone);

interface TabPanelProps {
	children?: React.ReactNode;
	index: number;
	value: number;
}

export function ActivityDetails() {
	const {guid} = useParams();
	const [isUpdated, setIsUpdated] = useState(true);
	const isMobile = useMediaQuery('(max-width: 600px)');
	const queryClient = useQueryClient();

	const renderIcon = () => {
		if (activity?.type === ActivityTypesEnum.Phone) {
			return <LocalPhoneIcon color="secondary"/>;
		} else if (activity?.type === ActivityTypesEnum.Online) {
			return <ConnectWithoutContactIcon color="secondary"/>;
		} else if (activity?.type === ActivityTypesEnum.Physical) {
			return <PeopleIcon color="secondary"/>;
		}
	}

	const {data: activity, status} = useQuery<IActivity>(
		['activity', isUpdated],
		() => fetchDataReactQuery(`/activities/${guid}`),
		{
			cacheTime: 0,
		}
	);

	/* activity cancellation dialog configuration >> */
	const [openDialog, setOpenDialog] = useState(false);

	const handleClickOpenDialog = () => {
		setOpenDialog(true);
	};
	/* << activity cancellation dialog configuration */

	/* tabs configuration >> */
	const [value, setValue] = useState(0);
	const handleChange = (event: React.SyntheticEvent, newValue: number) => {
		setValue(newValue);
	};

	function TabPanel(props: TabPanelProps) {
		const {children, value, index, ...other} = props;

		return (
			<div role="tabpanel"
				 hidden={value !== index}
				 id={`simple-tabpanel-${index}`}
				 aria-labelledby={`action-tab-${index}`}
				 {...other}>
				{value === index && (
					<Box sx={{p: 3}}>
						<Typography>{children}</Typography>
					</Box>
				)}
			</div>
		);
	}

	/* << tabs configuration */

	const activityMutation = useMutation(
		{
			mutationFn: (data: object) => postDataReactQuery(`/email/activity/${activity?.id}/confirm`, data),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const handleActivityEmailSend = () => {
		activityMutation.mutate([]);
	}

	if (status === 'loading') return <LoadingComponent/>;

	if (status === 'error') return <ErrorComponent type={ErrorTypesEnum.Fetch}/>;

	return (
		<Container sx={{
			flexGrow: 1,
			overflow: 'auto',
			py: 2
		}} maxWidth='lg'>
			<Grid container spacing={3}>
				{
					activity && (
						<>
							<Grid item xs={12} md={8} lg={9}>
								<Paper sx={{
									p: 2,
									display: "flex",
									flexDirection: 'column'
								}}>
									<Grid display="flex" justifyContent="space-between">
										<Grid>
											<Typography variant="h5" marginBottom={1}>
												{activity?.subject}
												{' '}
												{activity.tags.map((tag) => (
													<>
														<Chip variant="outlined" color="success"
															  label={tag.name}/> {' '}
													</>
												))}
											</Typography>
											<Typography variant="subtitle2" color="textSecondary" marginBottom={1}>
												{dayjs(activity?.start).tz('UTC').format('DD MMM YYYY HH:mm')}
											</Typography>
											<div style={{
												display: "flex",
												alignItems: "center",
												flexWrap: "wrap",
												marginBottom: 2
											}}>
												{renderIcon()}
												<Typography variant="caption" color="textSecondary">
													{"-"} {activity?.type}
												</Typography>
											</div>
										</Grid>
										<Grid>
											{!activity.emailSentAt && (
												<>
													{!isMobile ? (
														<Button variant="contained" color="primary"
																startIcon={<EmailIcon/>}
																onClick={handleActivityEmailSend}>
															Send email
														</Button>
													) : (
														<IconButton color="primary"
																	size="large"
																	onClick={handleActivityEmailSend}>
															<EmailIcon/>
														</IconButton>
													)}
												</>
											)}
										</Grid>
									</Grid>
									<Box sx={{borderBottom: 1, borderColor: 'divider'}}>
										<Tabs
											value={value}
											aria-label="activity tabs"
											variant="scrollable"
											scrollButtons="auto"
											allowScrollButtonsMobile
											onChange={handleChange}
										>
											<Tab label="Notes" {...allyProps(0)} />
											<Tab label="Tasks" {...allyProps(1)} />
											<Tab label="Participants" {...allyProps(2)} />
											<Tab label="Review" {...allyProps(3)} />
										</Tabs>
									</Box>

									{/*notes tab*/}
									<TabPanel value={value} index={0}>
										<ActivityExternalNote activity={activity}/>
										<ActivityInternalNote activity={activity}/>
										<ActivityVoiceMemo activity={activity}/>
									</TabPanel>

									{/*tasks tab*/}
									<TabPanel value={value} index={1}>
										<Grid
											container
											spacing={0}
											alignItems="center"
											justifyContent="center"
										>
											<Grid item xs={12} sm={10} md={8}
												  sx={{
													  bgcolor: '#edf3f0',
													  padding: '0 1em',
													  borderRadius: '10px',
													  display: 'flex',
													  alignItems: 'stretch',
													  marginBottom: 1,
													  minHeight: '10em'
												  }}>
												<ActivityTasks activityId={activity.id} activityGuid={activity.guid}/>
											</Grid>
										</Grid>
									</TabPanel>

									{/*participants tab*/}
									<TabPanel value={value} index={2}>
										<ParticipantsList contacts={activity?.contacts ?? []} activityId={activity.id}/>
										<Box display="flex" justifyContent="center" marginTop={2}>
											<ActivityParticipantsDialog activity={activity}
																		type={ParticipantTypesEnum.Participant}
																		setIsUpdated={setIsUpdated}/>
										</Box>
										<ExternalParticipantsList
											externalParticipants={activity?.externalParticipants ?? []}/>
										<Box display="flex" justifyContent="center" marginTop={2}>
											<ActivityParticipantsDialog activity={activity}
																		type={ParticipantTypesEnum.External_Participant}
																		setIsUpdated={setIsUpdated}/>
										</Box>
									</TabPanel>

									{/*activity review tab*/}
									<TabPanel value={value} index={3}>
										<ActivityReview activity={activity}/>
									</TabPanel>
								</Paper>
								<Grid mt={1}>
									<Button variant="text" color="error" startIcon={<DeleteIcon/>}
											onClick={handleClickOpenDialog}>
										Delete
									</Button>
									<ActivityCancelDialog open={openDialog} setOpen={setOpenDialog}
														  activity={activity} setIsUpdated={setIsUpdated}/>
								</Grid>
							</Grid>
							<Grid item xs={12} md={4} lg={3}>
								<ActivityAside activity={activity}/>
							</Grid>
						</>
					)
				}
			</Grid>
		</Container>
	);
}