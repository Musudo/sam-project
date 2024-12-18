import * as React from 'react';
import {IContact} from "../../../models/IContact";
import {Avatar, Chip, ListItem, ListItemAvatar, ListItemButton, ListItemText, Typography} from "@mui/material";
import {useNavigate} from "react-router-dom";
import {stringAvatar, stringColoredAvatar} from "../../../utils/AvatarGeneratorUtil";
import {dataFieldFormatter, nameFormatter} from "../../../utils/DataFormatterUtil";
import {IInstitution} from "../../../models/IInstitution";

interface Props {
	contact: IContact;
}

export function ContactItem({contact}: Props) {
	const fullName = nameFormatter(contact.firstName, contact.lastName);
	const jobTitle = dataFieldFormatter(contact.jobTitle);
	const phoneNumber = dataFieldFormatter(contact.phoneNumber1);
	const navigate = useNavigate();

	return (
		<ListItem disablePadding>
			<ListItemButton onClick={() => navigate(`/contacts/${contact.guid}`)}>
				<ListItemAvatar>
					{fullName === "na" ? <Avatar {...stringAvatar("n a")} /> :
						<Avatar {...stringColoredAvatar(fullName)} />}
				</ListItemAvatar>
				<ListItemText
					primary={
						<Typography>
							{`${fullName} `}
							<Chip label={jobTitle} color="default"/>
						</Typography>
					}
					secondary={
						<>
							<span style={{color: "black"}}>{`${contact.email1} - ${phoneNumber}`}</span>
							<br/>
							{contact.institutions.map((i: IInstitution) =>
								<span key={i.clientId}>{i.name} - {i.clientId}<br/></span>)}
						</>
					}
				/>
			</ListItemButton>
		</ListItem>
	);
}