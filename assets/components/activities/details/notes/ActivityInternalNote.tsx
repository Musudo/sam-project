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

export function ActivityInternalNote({activity}: Props) {
	const [isHovering, setIsHovering] = useState(false);
	const [isEditing, setIsEditing] = useState(false);
	const queryClient = useQueryClient();

	const handleEnterEditMode = () => setIsEditing(true);

	const handleCancelEditMode = () => {
		setIsEditing(false);
		setIsHovering(false);
	};

	const handleNoteUpdate = (internalNote: object) => modifyInternalNoteMutation.mutate(internalNote);

	const modifyInternalNoteMutation = useMutation(
		{
			mutationFn: (internalNote: object) => patchDataReactQuery(`/activities/${activity.id}/internal-note`, internalNote),
			onSuccess: () => {
				handleCancelEditMode();
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const {register, handleSubmit} = useForm<{
		internalNote: string | undefined;
	}>({
		defaultValues: {
			internalNote: activity?.internalNote
		}
	});

	return (
		<Box mb={2}
			 onMouseEnter={() => setIsHovering(true)}
			 onMouseLeave={() => setIsHovering(false)}>
			<Box mb={1} color="text.secondary">
				<Typography component="span" variant="body1">
					Internal note
				</Typography>
			</Box>
			{isEditing ? (
				<form onSubmit={handleSubmit(handleNoteUpdate)}>
					<Box>
						<TextField
							label="Internal note"
							variant="filled"
							fullWidth
							multiline
							{...register("internalNote")}/>
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
						{activity?.internalNote
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