import * as React from 'react';
import {Box, Button, Divider, Typography} from '@mui/material';
import EditIcon from '@mui/icons-material/Edit';
import {useNavigate} from "react-router-dom";
import {IContact} from "../../../models/IContact";
import {IInstitution} from "../../../models/IInstitution";
import {addressFormatter, dataFieldFormatter} from "../../../utils/DataFormatterUtil";

interface Props {
	contact: IContact;
}

export function ContactAside({contact}: Props) {
	const phoneNumber1 = dataFieldFormatter(contact.phoneNumber1);
	const phoneNumber2 = contact.phoneNumber2 ? dataFieldFormatter(contact.phoneNumber2) : null;
	const navigate = useNavigate();

	return (
		<Box ml={4} width={250} minWidth={250}>
			<Box textAlign="left" mb={2}>
				<Button type="button" startIcon={<EditIcon/>}
						onClick={() => navigate(`/contacts/edit/${contact.guid}`)}>Edit Contact</Button>
			</Box>
			<Typography variant="subtitle2" fontWeight="bold">Contact info</Typography>
			<Divider/>
			<Box mt={1} mb={2}>
				<Typography variant="body2">
					{contact.email1}
					{contact.email2 && <><br/>{contact.email2}</>}
					<br/>
					{phoneNumber1}
					{phoneNumber2 && <><br/>{contact.phoneNumber2}</>}
				</Typography>
			</Box>
			{contact.institutions.map((institution: IInstitution, index: number) => (
				<>
					<Typography variant="subtitle2" fontWeight="bold">Institution {index + 1}</Typography>
					<Divider/>
					<Box mt={1} mb={1}>
						<Typography variant="body2">
							{institution?.name}
						</Typography>
					</Box>
					<Box mt={1} mb={2}>
						<Typography variant="body2">
							{addressFormatter(institution.country, institution.city, institution.zipCode,
								institution.street, institution.houseNumber)}
						</Typography>
					</Box>
				</>
			))}
		</Box>
	);
}