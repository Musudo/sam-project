import React, {useContext, useEffect, useState} from "react";
import {ActivityForm} from "./ActivityForm";
import {
	Box,
	Button,
	Container,
	FormControlLabel,
	FormHelperText,
	Grid,
	IconButton,
	Paper,
	Step,
	StepLabel,
	Stepper,
	Switch,
	Tooltip,
	Typography
} from "@mui/material";
import {useForm} from "react-hook-form";
import {ParticipantForm} from "./ParticipantForm";
import {useNavigate, useParams} from "react-router-dom";
import AddIcon from "@mui/icons-material/Add";
import {ExternalParticipantForm} from "./ExternalParticipantForm";
import {fetchDataReactQuery, postDataReactQuery} from "../../../utils/HttpRequestUtil";
import dayjs from "dayjs";
import UserContext, {IUserContext} from "../../../context/UserContext";
import {IContact} from "../../../models/IContact";
import {IInstitution} from "../../../models/IInstitution";
import {ITag} from "../../../models/ITag";
import {useTranslation} from "react-i18next";
import {useMutation, useQuery, useQueryClient} from "@tanstack/react-query";
import {IActivity} from "../../../models/IActivity";
import {FormTypesEnum} from "../../../enums/ComponentPropsEnums";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

let index = 1;

interface IExternal {
	index: number;
}

export function ActivityCreate() {
	const [sendEmail, setSendEmail] = useState(false);
	const [externals, setExternals] = useState<IExternal[]>([]);
	// default institution, institutions and contacts should stay outside of participant form for now,
	// so that its value is not changed after each rerender of participant form
	const [institution, setInstitution] = useState<IInstitution | null>(null);
	const [contacts, setContacts] = useState<IContact[]>([]);
	const navigate = useNavigate();
	const {type} = useParams();
	const {user} = useContext<IUserContext>(UserContext);
	const {t} = useTranslation();
	const queryClient = useQueryClient();

	const {
		register,
		unregister,
		control,
		handleSubmit,
		setValue,
		trigger,
		formState: {errors}
	} = useForm<IActivity>({
		defaultValues: {
			subject: '',
			tags: [],
			externalNote: '',
			internalNote: '',
			type: type,
			// TODO: this doesn't make sense, but month and day should be flipped here, otherwise datetimepicker acts very weird -> fix this in the future
			start: dayjs().format('MM-DD-YYYY HH:mm'),
			// TODO: this doesn't make sense, but month and day should be flipped here, otherwise datetimepicker acts very weird -> fix this in the future
			end: dayjs().add(60, 'minutes').format('MM-DD-YYYY HH:mm'),
			user: user?.id,
			contacts: [],
			institution: null,
			externalParticipants: [],
			emailSentAt: null
		}
	});

	// reset user value, otherwise it's undefined
	useEffect(() => {
		setValue("user", user?.id ?? 0);
	}, [user]);

	const {data: tags} = useQuery<ITag[]>(
		['tags'],
		() => fetchDataReactQuery(`/tags`),
		{
			staleTime: 60 * 1000,
		}
	);

	const handleSendEmailChange = (event: any) => setSendEmail(event.target.checked);

	/* stepper configuration >> */
	const [activeStep, setActiveStep] = useState(0);
	const steps = [t('Activity form.Participants'), t('Activity form.Activity')];

	const participantStep = (
		<Grid container spacing={3}>
			<ParticipantForm control={control} errors={errors} setValue={setValue} contacts={contacts}
							 setContacts={setContacts} setInstitution={setInstitution} institution={institution}/>
			{externals.length > 0 && externals.map((external: IExternal) => (
				<Grid item xs={12}>
					<ExternalParticipantForm external={external} externals={externals} register={register}
											 unRegister={unregister} setExternals={setExternals}/>
				</Grid>
			))}
			<Grid item xs={12}>
				<Tooltip title={t('Activity form.Add someone external')}>
					<IconButton color="primary" aria-label="participants"
								onClick={() => setExternals([...externals, {index: index++}])}>
						<AddIcon/>
					</IconButton>
				</Tooltip>
			</Grid>
		</Grid>
	);

	const activityStep = (
		<>
			<ActivityForm register={register} controller={control} errors={errors} tags={tags || []}
						  activity={null} setValue={setValue} type={FormTypesEnum.Create}/>
			<Grid item xs={12} mt={2}>
				<FormControlLabel
					control={<Switch onChange={handleSendEmailChange}/>}
					label={t('Activity form.Send email')}
				/>
				<FormHelperText>
					{t('Activity form.Switch on to immediately send email')}
				</FormHelperText>
			</Grid>
		</>
	);

	function getStepContent(step: number) {
		switch (step) {
			case 0:
				return participantStep;
			case 1:
				return activityStep;
			default:
				throw new Error('Unknown step');
		}
	}

	const handleNext = () => {
		// make sure participants are chosen before going to next step
		trigger(["contacts"]).then(function (result) {
			if (result) setActiveStep(activeStep + 1);
		})
	};

	const handleBack = () => setActiveStep(activeStep - 1);
	/* << stepper configuration */

	const createActivityMutation = useMutation(
		{
			mutationFn: (activity: IActivity) => postDataReactQuery(`/activities`, activity),
			onSuccess: (data) => {
				if (sendEmail) {
					emailMutation.mutate(data.data.id);
				}
				navigate(`/activities`);
			}
		}
	);

	const emailMutation = useMutation({
			mutationFn: (id: number) => postDataReactQuery(`/email/activity/${id}/confirm`, [])
		}
	);

	function onSubmit(data: any) {
		// add external participants to activity object
		for (let i = 0; i < index; i++) {
			if (data["external-" + i]) {
				data.externalParticipants.push({"email": data["external-" + i]});
				delete data["external-" + i]
			}
		}

		// format start date and adjust its timezone
		data.start = dayjs(data.start).tz('UTC').format("DD-MM-YYYY HH:mm");
		// format start date and adjust its timezone
		data.end = dayjs(data.end).tz('UTC').format("DD-MM-YYYY HH:mm");

		if (sendEmail) {
			data.emailSentAt = dayjs().tz('UTC').format("DD-MM-YYYY HH:mm");
		} else {
			data.emailSentAt = null;
		}

		// prepare tags for the backend
		data.tags = tags
			?.filter(tag => data.tags.includes(tag.name))
			.map(tag => tag.id);

		createActivityMutation.mutate(data);
	}

	return (
		<Container component="main" maxWidth="sm" sx={{mb: 4}}>
			<form onSubmit={handleSubmit(onSubmit)}>
				<Paper variant="outlined" sx={{my: {xs: 3, md: 6}, p: {xs: 2, md: 3}}}>
					<Typography component="h1" variant="h4" align="center">
						{t('Activity form.New activity')}
					</Typography>
					<Stepper activeStep={activeStep} sx={{pt: 3, pb: 5}}>
						{steps.map((label) => (
							<Step key={label}>
								<StepLabel>{label}</StepLabel>
							</Step>
						))}
					</Stepper>
					{getStepContent(activeStep)}
					<Box sx={{display: 'flex', justifyContent: 'flex-end'}}>
						{activeStep !== 0 && (
							<Button type="button" onClick={handleBack} sx={{mt: 3, ml: 1}}>
								{t('Activity form.Back')}
							</Button>
						)}
						{activeStep !== steps.length - 1 && (
							<Button
								type="button"
								variant="contained"
								onClick={handleNext}
								sx={{mt: 3, ml: 1}}>
								{t('Activity form.Next')}
							</Button>
						)}
						{activeStep === steps.length - 1 && (
							<Button
								type="submit"
								variant="contained"
								sx={{mt: 3, ml: 1}}>
								{t('Activity form.Save')}
							</Button>
						)}
					</Box>
				</Paper>
			</form>
		</Container>
	);
}