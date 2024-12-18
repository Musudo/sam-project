import React, {useEffect, useState} from "react";
import {Controller} from "react-hook-form";
import {
	Box,
	Chip,
	FormControl,
	FormHelperText,
	Grid,
	InputLabel,
	MenuItem,
	OutlinedInput,
	Select, SelectChangeEvent,
	TextField
} from "@mui/material";
import {AdapterDayjs} from "@mui/x-date-pickers/AdapterDayjs";
import {DateTimePicker} from "@mui/x-date-pickers/DateTimePicker";
import {LocalizationProvider} from "@mui/x-date-pickers/LocalizationProvider";
import dayjs from "dayjs";
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import 'dayjs/locale/nl';
import 'dayjs/locale/fr';
import 'dayjs/locale/de';
import 'dayjs/locale/es';
import 'dayjs/locale/en-gb';
import {ActivityTypesEnum} from "../../../enums/ActivityTypesEnum";
import {ITag} from "../../../models/ITag";
import {IActivity} from "../../../models/IActivity";
import {useTranslation} from "react-i18next";
import {getLocale} from "../../../utils/LocaleGeneratorUtil";
import {MenuProps} from "../../../props/MUIElementProps";
import {FormTypesEnum} from "../../../enums/ComponentPropsEnums";

dayjs.extend(utc);
dayjs.extend(timezone);

interface Props {
	register: any;
	controller: any;
	errors: any;
	tags: ITag[] | [];
	activity: IActivity | null;
	setValue: any;
	type: FormTypesEnum;
}

export function ActivityForm(props: Props) {
	const activityTypes = Object.keys(ActivityTypesEnum);
	const [endMinimalValue, setEndMinimalValue] = useState(dayjs());
	const {t, i18n} = useTranslation();
	const [locale, setLocale] = useState<string>("en-gb");
	const oldTags: string[] = props.activity?.tags.map((tag: ITag) => tag.name) ?? [];

	// change datetime locale when language is changed
	useEffect(() => {
		setLocale(getLocale(i18n.language));
	}, [i18n.language]);

	const handleStartTimeChange = (value: any) => {
		setEndMinimalValue(value);
		props.setValue("end", dayjs(value).add(60, 'minutes'));
	}

	const handleTagSelectorChange = (element: SelectChangeEvent<string[]>) => {
		let tagNames = element.target.value;
		let tagsTemp = props.tags.filter((tag: ITag) => tagNames.includes(tag.name));
		props.setValue('tags', tagsTemp);
	}

	return (
		<Grid container spacing={3}>
			<Grid item xs={12}>
				<FormControl variant="standard" fullWidth sx={{minWidth: 120}}>
					<InputLabel id="typeLabel">{t('Activity form.Type')}</InputLabel>
					<Controller
						name="type"
						control={props.controller}
						render={({field}) => (
							<Select
								{...field}
								labelId="typeLabel"
							>
								{
									activityTypes.map((activityType: string) => (
										<MenuItem key={activityType} value={activityType}>
											{activityType}
										</MenuItem>
									))
								}
							</Select>
						)}
					/>
				</FormControl>
			</Grid>
			<Grid item xs={12}>
				<TextField
					label={t('Activity form.Subject')}
					variant="standard"
					InputLabelProps={{shrink: true}}
					fullWidth
					{...props.register("subject", {
						required: "Subject is required",
						maxLength: {value: 50, message: "Subject may not be longer than 50 characters"}
					})}
				/>
				<FormHelperText error>{props?.errors?.subject?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12} sm={6}>
				<Controller
					name="start"
					control={props.controller}
					rules={{required: "Start time is required"}}
					render={({field: {value, onChange}}) => (
						<FormControl variant="standard" fullWidth sx={{minWidth: 120, marginTop: 2}}>
							<LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale={locale}>
								<DateTimePicker
									label={t('Activity form.Start time')}
									minDateTime={dayjs()}
									minutesStep={5}
									value={value}
									reduceAnimations={true}
									onChange={onChange}
									onAccept={(value: any) => handleStartTimeChange(value)}
									renderInput={(props) => <TextField {...props} />}
								/>
							</LocalizationProvider>
						</FormControl>
					)}
				/>
				<FormHelperText error>{props?.errors?.start?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12} sm={6}>
				<Controller
					name="end"
					control={props.controller}
					rules={{required: "End time is required"}}
					render={({field: {value, onChange}}) => (
						<FormControl variant="standard" fullWidth sx={{minWidth: 120, marginTop: 2}}>
							<LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale={locale}>
								<DateTimePicker
									label={t('Activity form.End time')}
									minDateTime={endMinimalValue}
									minutesStep={5}
									value={value}
									reduceAnimations={true}
									onChange={onChange}
									renderInput={(props) => <TextField {...props}/>}
								/>
							</LocalizationProvider>
						</FormControl>
					)}
				/>
				<FormHelperText error>{props?.errors?.end?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12}>
				<FormControl fullWidth sx={{minWidth: 120}}>
					<InputLabel id="tagLabelId">{t('Activity form.Tags')}</InputLabel>
					<Controller
						name="tags"
						control={props.controller}
						rules={{required: "Tag is required"}}
						render={({field}) => (
							props.type === FormTypesEnum.Create ? (
								/* selector behavior when creating activity */
								<Select
									{...field}
									multiple
									id="tagSelect"
									labelId="tagLabelId"
									input={<OutlinedInput label={t('Activity form.Tags')}/>}
									renderValue={(selected) => (
										<Box sx={{display: 'flex', flexWrap: 'wrap', gap: 0.5}}>
											{selected.map((tag: string, index: number) => (
												<Chip color="success" key={index} label={tag}/>
											))}
										</Box>
									)}
									MenuProps={MenuProps}
								>
									{props.tags.map((tag: ITag) => (
										<MenuItem
											key={tag.name}
											value={tag.name}
										>
											{tag.name}
										</MenuItem>
									))}
								</Select>
							) : (
								/* selector behavior when editing activity */
								<Select
									{...field}
									multiple
									id="tagSelect"
									labelId="tagLabelId"
									defaultValue={oldTags}
									input={<OutlinedInput label={t('Activity form.Tags')}/>}
									renderValue={(selected) => (
										<Box sx={{display: 'flex', flexWrap: 'wrap', gap: 0.5}}>
											{selected.map((tag: string, index: number) => (
												<Chip color="success" key={index} label={tag}/>
											))}
										</Box>
									)}
									MenuProps={MenuProps}
									onChange={(element: SelectChangeEvent<string[]>) => handleTagSelectorChange(element)}
								>
									{props.tags.map((tag: ITag) => (
										<MenuItem
											key={tag.name}
											value={tag.name}
										>
											{tag.name}
										</MenuItem>
									))}
								</Select>
							)

						)}
					/>
				</FormControl>
				<FormHelperText error>{props?.errors?.tags?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12}>
				<TextField
					label={t('Activity form.External note')}
					FormHelperTextProps={{style: {color: "#f57c00"}}}
					helperText={t('Activity form.Client will be able to see this note')}
					variant="filled"
					color="warning"
					fullWidth
					multiline
					rows={2}
					focused
					{...props.register("externalNote")}
				/>
			</Grid>
			<Grid item xs={12}>
				<TextField
					label={t('Activity form.Internal note')}
					variant="filled"
					fullWidth
					multiline
					rows={2}
					focused
					{...props.register("internalNote")}
				/>
			</Grid>
		</Grid>
	);
}