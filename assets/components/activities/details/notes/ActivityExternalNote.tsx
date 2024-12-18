import React, {useState} from 'react';
import {Box, Button, IconButton, TextField, Tooltip, Typography} from "@mui/material";
import EditIcon from "@mui/icons-material/Edit";
import {IActivity} from "../../../../models/IActivity";
import {useForm} from "react-hook-form";
import {patchDataReactQuery} from "../../../../utils/HttpRequestUtil";
import {useMutation, useQueryClient} from "@tanstack/react-query";

interface Props {
	activity: IActivity;
}

export function ActivityExternalNote({activity}: Props) {
	const [isHovering, setIsHovering] = useState(false);
	const [isEditing, setIsEditing] = useState(false);
	const queryClient = useQueryClient();

	const handleEnterEditMode = () => setIsEditing(true);

	const handleCancelEditMode = () => {
		setIsEditing(false);
		setIsHovering(false);
	};

	const handleNoteUpdate = (note: object) => modifyExternalNoteMutation.mutate(note);

	const modifyExternalNoteMutation = useMutation(
		{
			mutationFn: (data: object) => patchDataReactQuery(`/activities/${activity.id}/external-note`, data),
			onSuccess: () => {
				handleCancelEditMode();
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const {register, handleSubmit} = useForm<{
		externalNote: string | undefined;
	}>({
		defaultValues: {
			externalNote: activity?.externalNote
		}
	});

	return (
		<Box mb={2}
			 onMouseEnter={() => setIsHovering(true)}
			 onMouseLeave={() => setIsHovering(false)}>
			<Box mb={1} color="text.secondary">
				<Typography component="span" variant="body1">
					External note (client will be able to see this note)
				</Typography>
			</Box>
			{isEditing ? (
				<form onSubmit={handleSubmit(handleNoteUpdate)}>
					<Box>
						<TextField
							label="External note"
							variant="filled"
							fullWidth
							multiline
							{...register("externalNote")}/>
					</Box>
					<Box display="flex" justifyContent="flex-end" mt={1}>
						<Button
							sx={{mr: 1}}
							onClick={handleCancelEditMode}
							color="primary"
							size="small"
						>
							Cancel
						</Button>
						<Button
							type="submit"
							color="primary"
							variant="contained"
							size="small"
						>
							Update Note
						</Button>
					</Box>
				</form>
			) : (
				<Box sx={{
					bgcolor: '#edf3f0',
					padding: '0 1em',
					borderRadius: '10px',
					display: 'flex',
					alignItems: 'stretch',
					marginBottom: 1,
					minHeight: '4em'
				}}>
					<Box flex={1}>
						{activity?.externalNote
							?.split('\n')
							.map((paragraph: string, index: number) => (
								<Box
									component="p"
									fontFamily="fontFamily"
									fontSize="body1.fontSize"
									lineHeight={1.3}
									marginBottom={2.4}
									key={index}
								>
									{paragraph}
								</Box>
							))}
					</Box>
					<Box sx={{
						marginLeft: 2,
						display: 'flex',
						flexDirection: 'column',
						justifyContent: 'space-around',
						visibility: isHovering ? 'visible' : 'hidden',
					}}>
						<Tooltip title="Edit note">
							<IconButton
								size="small"
								onClick={handleEnterEditMode}
							>
								<EditIcon/>
							</IconButton>
						</Tooltip>
					</Box>
				</Box>
			)}
		</Box>
	);
}