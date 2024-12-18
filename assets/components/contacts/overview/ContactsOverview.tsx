import React, {useEffect, useState} from "react";
import {IContact} from "../../../models/IContact";
import {useNavigate} from "react-router-dom";
import {Box, Button, Container, Grid, Pagination, Paper, Stack, TablePagination, useMediaQuery} from "@mui/material";
import AddIcon from '@mui/icons-material/Add';
import {ContactsOverviewAside} from "./ContactsOverviewAside";
import {useQuery} from "@tanstack/react-query";
import {ErrorComponent} from "../../ErrorComponent";
import ContactSearchBar from "./ContactSearchBar";
import {useTranslation} from "react-i18next";
import ContactsList from "./ContactsList";
import {fetchDataReactQuery} from "../../../utils/HttpRequestUtil";
import {ErrorTypesEnum} from "../../../enums/ErrorTypesEnum";

export function ContactsOverview() {
	const [filteredContacts, setFilteredContacts] = useState<IContact[]>([]);
	const [searchValue, setSearchValue] = useState<string>("");
	const navigate = useNavigate();
	const isMobile = useMediaQuery('(max-width: 600px)');
	const {t} = useTranslation();

	/* pagination configuration >> */
	const [page, setPage] = useState(0);
	const [rowsPerPage, setRowsPerPage] = useState(10);

	const handleChangePage = (event: any, newPage: number) => setPage(newPage);

	const handleChangeRowsPerPage = (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
		setRowsPerPage(parseInt(event.target.value, 10));
		setPage(0);
	};
	/* << pagination configuration */

	let url = searchValue !== "" ? `/contacts/info/${searchValue}` : `/contacts`;

	const {data: contacts, status} = useQuery<IContact[]>(
		['contacts', searchValue],
		() => fetchDataReactQuery(url)
	);

	useEffect(() => {
		if (status === "success" && contacts) {
			setFilteredContacts(contacts);
			setPage(0);
		}
	}, [contacts, status]);

	function handleJobTitleFilter(jobTitle: string) {
		if (jobTitle === 'Show all') {
			setFilteredContacts(contacts as IContact[]);
		} else {
			const contactsTemp = (contacts && contacts.length > 0)
				? contacts.filter((contact: IContact) => contact.jobTitle === jobTitle)
				: '';
			setFilteredContacts(contactsTemp as IContact[]);
		}
		setPage(0);
	}

	if (status === 'error') return <ErrorComponent type={ErrorTypesEnum.Fetch}/>;

	return (
		<Container sx={{
			flexGrow: 1,
			overflow: 'auto',
			py: 2
		}} maxWidth='lg'>
			<Grid sx={{display: 'flex', justifyContent: 'end', marginBottom: '1em'}}>
				<Button variant='contained'
						startIcon={<AddIcon/>}
						fullWidth={isMobile}
						onClick={() => navigate(`/contacts/new`)}>
					{t('Contacts overview page.New contact')}
				</Button>
			</Grid>

			{/*show searchbar for mobile*/}
			{isMobile && <><ContactSearchBar setSearchValue={setSearchValue}/><br/></>}

			<Grid container spacing={3}>
				{/*show contacts overview aside for desktop view*/}
				{!isMobile && (
					<Grid item xs={12} md={4} lg={3}>
						<ContactsOverviewAside setSearchValue={setSearchValue}
											   handleJobTitleFilter={handleJobTitleFilter}/>
					</Grid>
				)}

				<Grid item xs={12} md={8} lg={9}>
					<Paper
						sx={{
							p: 0,
							display: "flex",
							flexDirection: "column",
						}}>
						<ContactsList page={page} rowsPerPage={rowsPerPage} filteredContacts={filteredContacts}/>
					</Paper>

					{/*show pagination for desktop view*/}
					{!isMobile ? (
						<Box sx={{display: 'flex', justifyContent: 'flex-end', padding: 2}}>
							<TablePagination
								component="div"
								count={(filteredContacts && filteredContacts.length) ?? 0}
								page={page}
								onPageChange={handleChangePage}
								rowsPerPage={rowsPerPage}
								onRowsPerPageChange={handleChangeRowsPerPage}
							/>
						</Box>
					) : (
						/*show pagination for mobile view*/
						<Box sx={{display: 'flex', justifyContent: 'center', padding: 2}}>
							<Stack spacing={2}>
								<Pagination
									count={(filteredContacts && filteredContacts.length) ?? 0}
									boundaryCount={0}
									onChange={handleChangePage}/>
							</Stack>
						</Box>
					)}

				</Grid>
			</Grid>
		</Container>
	);
}
