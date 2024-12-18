import React from 'react';
import {Avatar, IconButton, List, ListItem, ListItemAvatar, ListItemText, Tooltip, Typography} from "@mui/material";
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import HighlightOffIcon from "@mui/icons-material/HighlightOff";
import {patchDataReactQuery} from "../../../../utils/HttpRequestUtil";
import {IContact} from "../../../../models/IContact";
import {useMutation, useQueryClient} from "@tanstack/react-query";

interface Props {
	activityId: number;
	contacts?: IContact[];
}

export function ParticipantsList({contacts, activityId}: Props) {
	const queryClient = useQueryClient();

	const deleteParticipantMutation = useMutation(
		{
			mutationFn: (data: object) => patchDataReactQuery(`/activities/${activityId}/contact`, data),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const removeParticipant = (contact: IContact) => deleteParticipantMutation.mutate(contact);

	return (
		<>
			{
				contacts?.map(contact => (
					<List sx={{width: '100%', maxWidth: 800, bgcolor: 'background.paper'}}>
						<ListItem key={contact.id}
								  secondaryAction={
									  <Tooltip title="Remove from activity">
										  <IconButton edge="end"
													  aria-label="Remove from activity"
													  color="default"
													  onClick={() => removeParticipant(contact)}
										  >
											  <HighlightOffIcon/>
										  </IconButton>
									  </Tooltip>

								  }
								  disablePadding>
							<ListItemAvatar>
								<Avatar>
									<AccountCircleIcon/>
								</Avatar>
							</ListItemAvatar>
							<ListItemText id={contact.firstName + contact.lastName}
										  primary={
											  <Typography>{`${contact.firstName} ${contact.lastName}`}</Typography>}
										  secondary={<span>{`${contact.email1}, ${contact.phoneNumber1}`}</span>}/>
						</ListItem>
					</List>
				))
			}
		</>
	);
}