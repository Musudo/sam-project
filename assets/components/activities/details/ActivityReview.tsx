import React, {SyntheticEvent, useEffect, useMemo, useState} from 'react';
import {
	Autocomplete,
	AutocompleteRenderInputParams,
	Box,
	Button,
	Chip,
	FormHelperText,
	IconButton,
	InputLabel,
	List,
	ListItem,
	ListItemText,
	ListSubheader,
	TextField,
	useMediaQuery
} from "@mui/material";
import {IActivity} from "../../../models/IActivity";
import {
	BasicFormattingButtonGroup,
	DataTransferButtonGroup,
	HeadingLevelButtonGroup,
	HistoryButtonGroup,
	OnChangeHTML,
	Remirror,
	ThemeProvider,
	Toolbar,
	useRemirror,
	VerticalDivider
} from '@remirror/react';
import {debounce} from "lodash";
import SaveIcon from '@mui/icons-material/Save';
import SendIcon from '@mui/icons-material/Send';
import {BoldExtension, HeadingExtension, ItalicExtension, UnderlineExtension} from "remirror/extensions";
import {AllStyledComponent} from "@remirror/styles/emotion";
import {deleteDataReactQuery, patchDataReactQuery, postDataReactQuery} from "../../../utils/HttpRequestUtil";
import AttachFileIcon from '@mui/icons-material/AttachFile';
import isEmail from 'validator/lib/isEmail';
import ClearIcon from '@mui/icons-material/Clear';
import {IAttachment} from "../../../models/IAttachment";
import {useMutation, useQueryClient} from "@tanstack/react-query";

interface Props {
	activity: IActivity;
}

const maxFiles = 4; // maximum allowed files

export function ActivityReview({activity}: Props) {
	const [newContent, setNewContent] = useState(activity.review?.content ?? "");
	const [newTitle, setNewTitle] = useState(activity.review?.title ?? "Review: ");
	// array of already existing attachments
	const [existingAttachments, setExistingAttachments] = useState<IAttachment[] | undefined>();
	// array of attachments files to persist to database
	const [attachments, setAttachments] = useState<File[]>([]);
	// list of names of attachments to show on the frontend
	const [attachmentsNames, setAttachmentsNames] = useState<string[]>([]);
	// state which will help to remove existing attachments from database
	// create a list of all available recipients
	const recipientsList = [
		...activity.contacts.map(c => c.email1),
		...activity.externalParticipants?.map(ep => ep.email) ?? []
	];
	const [sendIsDisabled, setSendIsDisabled] = useState(true);
	const [saveIsDisabled, setSaveIsDisabled] = useState(true);
	const [isSaved, setIsSaved] = useState(false);
	const [isSent, setIsSent] = useState(false);
	const [isEditorError, setIsEditorError] = useState(false);
	const isMobile = useMediaQuery('(max-width: 600px)');
	const queryClient = useQueryClient();

	let isMaxFileError: boolean = attachmentsNames.length >= maxFiles;

	// set states of existing attachments and attachments names list on page rendering
	useEffect(() => {
		setExistingAttachments(activity.review?.attachments);

		activity.review?.attachments.forEach((attachment) => {
			setAttachmentsNames((prevAttachmentsNames: string[]) => [...prevAttachmentsNames, attachment.path.split("/")[4]]);
		});
	}, []);

	/* recipients autocomplete configuration >> */
	const [selectedRecipients, setSelectedRecipients] = useState([]);
	const [inputValue, setInputValue] = useState("");
	const [emailError, setEmailError] = useState(false);
	let emailValidationErr = false;

	const handleAutocompleteChange = (event: SyntheticEvent, value: any) => {
		// validate all emails that are filled in
		value.forEach((email: string) => {
			if (!isEmail(email)) {
				emailValidationErr = true;
				return;
			} else {
				emailValidationErr = false;
			}
		});

		if (emailValidationErr) {
			// set value displayed in the text box
			setEmailError(true);
		} else {
			setEmailError(false);

			// update state of recipients array
			setSelectedRecipients(value);
			// make sure send button is disabled if there are no valid recipient emails filled in
			setSendIsDisabled(value.length <= 0);
		}
	}

	const onAutocompleteInputChange = (event: any, newValue: any) => setInputValue(newValue);

	const onDelete = (value: any) => {
		setSelectedRecipients(selectedRecipients.filter((e) => e !== value));
		// subtract 1, because at this point your state has the old value for some reason
		setSendIsDisabled(selectedRecipients.length - 1 <= 0);
	}
	/* << recipients autocomplete configuration */

	/* rich editor configuration >> */
	function EditorToolbar() {
		return (
			<Toolbar>
				<HistoryButtonGroup/>
				<VerticalDivider/>
				<DataTransferButtonGroup/>
				<VerticalDivider/>
				<HeadingLevelButtonGroup/>
				<VerticalDivider/>
				<BasicFormattingButtonGroup/>
				<VerticalDivider/>
			</Toolbar>
		);
	}

	const extensions = () => [
		new HeadingExtension(),
		new BoldExtension(),
		new ItalicExtension(),
		new UnderlineExtension(),
	];

	const {manager, state} = useRemirror({
		extensions,
		content: newContent,
		selection: "end",
		stringHandler: "html"
	});

	const handleEditorChangeHTML = (htmlData: any) => {
		setNewContent(htmlData);
		setSaveIsDisabled(false);
	};

	const debouncedEditorChangeHandler = useMemo(
		() => debounce(handleEditorChangeHTML, 500)
		, []);

	const handleTitleChange = (event: any) => {
		setNewTitle(event.target.value);
		setSaveIsDisabled(false);
	};

	const debouncedTitleChangeHandler = useMemo(
		() => debounce(handleTitleChange, 500)
		, []);

	const createReviewMutation = useMutation(
		{
			mutationFn: (data: object) => postDataReactQuery('/reviews', data),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const modifyReviewMutation = useMutation(
		{
			mutationFn: (data: object) => patchDataReactQuery(`/reviews/${activity.review?.id}`, data),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const saveReview = () => {
		// prepare review object for the backend
		const review = {
			title: newTitle,
			content: newContent,
			activity: activity.id,
			user: activity.user
		}

		// if there is no review yet then add new one, otherwise just update existing one
		if (!activity.review?.id) {
			createReviewMutation.mutate(review);
		} else {
			modifyReviewMutation.mutate(review);
		}

		setIsEditorError(false);
		setSaveIsDisabled(true);
		setSendIsDisabled(false);

		// review saving feedback animation
		setIsSaved(true);
		setTimeout(() => {
			setIsSaved(false);
		}, 3000);
	}
	/* << rich editor configuration */

	const handleAttachment = (event: any) => {
		const files: FileList | null = event.target.files;

		if (files) {
			// prepare attachments state
			const newAttachments: File[] = Array.from(files);
			setAttachments((prevAttachments: File[]) => [...prevAttachments, ...newAttachments]);
			// setAttachments(attachments => newAttachments);

			// prepare state with attachments names
			newAttachments.forEach((newAttachment: File) => {
				setAttachmentsNames((prevAttachmentsNames: string[]) => [...prevAttachmentsNames, newAttachment.name]);
			})
		}

		isMaxFileError = attachmentsNames.length >= maxFiles;
	}

	const handleAttachmentDelete = (name: string) => {
		// remove file object from array of uploaded attachments
		setAttachments(attachments.filter((a) => a.name != name));

		// remove name from names list
		setAttachmentsNames(attachmentsNames.filter((a) => a != name));

		// remove existing attachment from database
		existingAttachments?.forEach((a) => {
			if (a.path.includes(name)) {
				deleteExistingAttachment(a.id);
			}
		})

		isMaxFileError = attachmentsNames.length >= maxFiles;
	}

	const deleteAttachmentMutation = useMutation(
		{
			mutationFn: (id: number) => deleteDataReactQuery(`/reviews/attachment/${id}`),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const deleteExistingAttachment = (id: number) => {
		deleteAttachmentMutation.mutate(id);
	};

	const handleReviewSend = () => {
		// at first make sure review has been saved to the database,
		// otherwise show error message
		if (activity.review) {
			saveAttachment(activity.review.id);
			setTimeout(() => sendEmail(activity.review?.id ?? 0), 500);
		} else {
			setIsEditorError(true);
		}
	};

	const createAttachmentMutation = useMutation(
		{
			mutationFn: (data: any) => postDataReactQuery(`/file/attachment/${activity.review?.id}`, data),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const saveAttachment = (id: number) => {
		// prepare and send attachments
		const formData = new FormData();
		for (let i = 0; i < attachments.length + 1; i++) {
			formData.append(`attachment-${i}`, attachments[i]);
		}
		createAttachmentMutation.mutate(formData);

		// empty attachments after everything is saved to database
		setAttachments([]);
	};

	const createEmailMutation = useMutation(
		(variables: any) => {
			const {id, data} = variables;
			return postDataReactQuery(`/email/review/${id}`, data);
		},
		{
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			},
		}
	);

	const sendEmail = (id: number) => {
		// prepare and send email data
		const data = {recipients: selectedRecipients}
		createEmailMutation.mutate({id, data});

		// email sending feedback animation
		setIsSent(true);
		setTimeout(() => setIsSent(false), 3000);
	};

	return (
		<Box>
			<Box mb={1}>
				<InputLabel shrink htmlFor="multipleRecipients">
					Recipients
				</InputLabel>
				<Autocomplete
					multiple
					freeSolo
					limitTags={2}
					id="multipleRecipients"
					value={selectedRecipients}
					inputValue={inputValue}
					options={recipientsList}
					getOptionLabel={(option) => option}
					size="small"
					fullWidth
					onChange={(event: SyntheticEvent, value) => handleAutocompleteChange(event, value)}
					onInputChange={onAutocompleteInputChange}
					renderTags={(value: readonly string[], getTagProps) =>
						value.map((option: string, index: number) => (
							<Chip
								variant="outlined"
								label={option}
								{...getTagProps({index})}
								onDelete={() => onDelete(option)}
							/>
						))
					}
					renderInput={(params: AutocompleteRenderInputParams) => (
						<TextField
							{...params}
							type="email"
							placeholder="Add recipient"
							color="secondary"
							error={emailError}
							helperText={emailError && "Enter a valid email address"}
							InputLabelProps={{children: null}} // otherwise Textfield gives error because of params
						/>
					)}
				/>
			</Box>
			<Box>
				<InputLabel shrink htmlFor="titleInput">
					Title
				</InputLabel>
				<TextField
					id="titleInput"
					variant="outlined"
					InputLabelProps={{shrink: true}}
					size="small"
					color="secondary"
					sx={{marginBottom: 2}}
					defaultValue={newTitle}
					fullWidth
					onChange={debouncedTitleChangeHandler}/>
			</Box>
			<Box>
				<AllStyledComponent>
					<ThemeProvider>
						<Remirror placeholder='Enter text...'
								  manager={manager}
								  initialContent={state}
								  autoFocus
								  autoRender="end">
							<OnChangeHTML onChange={debouncedEditorChangeHandler}/>
							<EditorToolbar/>
						</Remirror>
					</ThemeProvider>
				</AllStyledComponent>
			</Box>
			<Box mt={1} display="flex" justifyContent="space-between">
				{/*show review functionality buttons for desktop*/}
				{!isMobile ? (
					<>
						<Box>
							<Button variant="text" color="primary" startIcon={<SaveIcon/>}
									disabled={saveIsDisabled}
									onClick={saveReview}>
								Save
							</Button>
							<Box display="flex" justifyContent="start">
								<FormHelperText sx={{
									opacity: isSaved ? 1 : 0,
									transition: 'opacity 0.3s ease-in-out'
								}}>
									Saved...
								</FormHelperText>
							</Box>
						</Box>
						<Box>
							<Button variant="text" color="primary" startIcon={<AttachFileIcon/>} component="label">
								Attach File
								<input hidden
									   multiple
									   type="file"
									   accept=".csv, .txt, .doc, .docx, .xls, .xlsx, .pdf"
									   disabled={isMaxFileError}
									   onChange={handleAttachment}/>
							</Button>
							<Button variant="text" color="primary" startIcon={<SendIcon/>}
									sx={{marginLeft: 1}}
									disabled={sendIsDisabled}
									onClick={handleReviewSend}>
								Send
							</Button>
							<Box display="flex" justifyContent="end">
								<FormHelperText sx={{
									opacity: isSent ? 1 : 0,
									transition: 'opacity 0.3s ease-in-out'
								}}>Sent...</FormHelperText>
							</Box>
						</Box>
					</>
				) : (
					/*show review functionality buttons for mobile*/
					<>
						<Box display="flex" justifyContent="start">
							<IconButton color="primary" aria-label="save" disabled={saveIsDisabled}
										onClick={saveReview}>
								<SaveIcon/>
							</IconButton>
						</Box>
						<Box display="flex" justifyContent="end">
							<Button variant="text" color="primary" startIcon={<AttachFileIcon/>} component="label">
								<input hidden
									   multiple
									   type="file"
									   accept=".csv, .txt, .doc, .docx, .xls, .xlsx, .pdf"
									   disabled={isMaxFileError}
									   onChange={handleAttachment}/>
							</Button>
							<IconButton color="primary" aria-label="send" disabled={sendIsDisabled}
										onClick={handleReviewSend}>
								<SendIcon/>
							</IconButton>
						</Box>
					</>
				)}
			</Box>
			{/*show email sending feedback for mobile */}
			{isMobile && (
				<Box display="flex" justifyContent="end">
					<FormHelperText sx={{
						opacity: isSent ? 1 : 0,
						transition: 'opacity 0.3s ease-in-out'
					}}>Sent...</FormHelperText>
				</Box>
			)}
			<Box display="flex" justifyContent="end">
				<List
					sx={{
						width: '100%',
						maxWidth: 200,
						bgcolor: 'background.paper'
					}}
					subheader={
						<ListSubheader component="div" id="nested-list-subheader">
							{attachmentsNames.length > 0 ? "Attached files:" : ""}
						</ListSubheader>
					}>
					{attachmentsNames.length > 0 && attachmentsNames.map((attachmentsName: string) => (
						<ListItem
							key={attachmentsName}
							disableGutters
							dense
							disablePadding
							secondaryAction={
								<IconButton aria-label="delete attachment"
											disableRipple
											onClick={() => handleAttachmentDelete(attachmentsName)}>
									<ClearIcon fontSize="small"/>
								</IconButton>
							}
						>
							<ListItemText
								primaryTypographyProps={{
									fontSize: 12,
									fontWeight: 300,
									color: '#666666'
								}}
								primary={attachmentsName}
							/>
						</ListItem>
					))}
				</List>
			</Box>
			<Box display="flex" justifyContent="end">
				<FormHelperText
					sx={{color: "orange"}}>{isMaxFileError && "Maximum amount files allowed to upload is 4"}</FormHelperText>
			</Box>
			<Box display="flex" justifyContent="end">
				<FormHelperText error>{isEditorError && "Save review before you send it"}</FormHelperText>
			</Box>
		</Box>
	);
}