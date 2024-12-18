import {IContact} from "../../../models/IContact";
import {ContactItem} from "./ContactItem";
import {Divider, List} from "@mui/material";
import React, {Fragment} from "react";

interface Props {
	page: number;
	rowsPerPage: number;
	filteredContacts: IContact[] | [];
}

export default function ContactsList(props: Props) {
	return (
		<List dense sx={{width: '100%', maxWidth: 1200, bgcolor: 'background.paper'}}>
			{(props.filteredContacts && props.filteredContacts.length > 0) && (
				props.filteredContacts
					?.slice(props.page * props.rowsPerPage, props.page * props.rowsPerPage + props.rowsPerPage)
					.map((contact: IContact, index: number) => (
						<Fragment key={index}>
							<ContactItem contact={contact}/>
							<Divider variant="inset" component="li"/>
						</Fragment>
					))
			)}
		</List>
	);
}