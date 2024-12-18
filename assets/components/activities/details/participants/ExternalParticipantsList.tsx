import React from 'react';
import {
	Avatar,
	Divider,
	IconButton,
	List,
	ListItem,
	ListItemAvatar,
	ListItemText,
	Tooltip,
	Typography
} from "@mui/material";
import EmailIcon from "@mui/icons-material/Email";
import {IExternalParticipant} from "../../../../models/IExternalParticipant";
import PersonAddOutlinedIcon from '@mui/icons-material/PersonAddOutlined';
import HighlightOffIcon from '@mui/icons-material/HighlightOff';
import {useNavigate} from "react-router-dom";
import {deleteDataReactQuery} from "../../../../utils/HttpRequestUtil";
import {useMutation, useQueryClient} from "@tanstack/react-query";

interface Props {
	externalParticipants?: IExternalParticipant[];
}

export function ExternalParticipantsList({externalParticipants}: Props) {
	const navigate = useNavigate();
	const queryClient = useQueryClient();

	const deleteExternalParticipantMutation = useMutation(
		{
			mutationFn: (id: number) => deleteDataReactQuery(`/external-participants/${id}`),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const removeExternalParticipant = (id: number) => deleteExternalParticipantMutation.mutate(id);

	return (
		<>
			<Typography variant="subtitle1" mt={3}>External participants</Typography>
			<Divider/>
			{
				externalParticipants?.map(externalParticipant => (
					<List sx={{width: '100%', maxWidth: 800, bgcolor: 'background.paper'}}>
						<ListItem key={externalParticipant.id} sx={{marginBottom: 1}}
								  secondaryAction={
									  <>
										  <Tooltip title="Add as a contact">
											  <IconButton edge="end"
														  aria-label="Add as a contact"
														  color="primary"
														  sx={{marginRight: 1}}
														  onClick={() => navigate(`/contacts/new/${externalParticipant.email}`)}
											  >
												  <PersonAddOutlinedIcon/>
											  </IconButton>
										  </Tooltip>
										  <Tooltip title="Remove from activity">
											  <IconButton edge="end"
														  aria-label="Remove from activity"
														  color="default"
														  onClick={() => removeExternalParticipant(externalParticipant.id)}
											  >
												  <HighlightOffIcon/>
											  </IconButton>
										  </Tooltip>
									  </>
								  }
								  disablePadding>
							<ListItemAvatar>
								<Avatar>
									<EmailIcon/>
								</Avatar>
							</ListItemAvatar>
							<ListItemText id={externalParticipant.email}
										  primary={<Typography>{externalParticipant.email}</Typography>}/>
						</ListItem>
					</List>
				))
			}
		</>
	);
}