import React, {Dispatch, Fragment, SetStateAction} from 'react';
import {Box, Chip, List, ListItem, Stack} from "@mui/material";
import ContactSearchBar from "./ContactSearchBar";
import LabelIcon from "@mui/icons-material/Label";
import Typography from "@mui/material/Typography";
import {useTranslation} from "react-i18next";
import {JobTitlesEnum} from "../../../enums/JobTitlesEnum";

interface Props {
	setSearchValue: Dispatch<SetStateAction<string>>
	handleJobTitleFilter: (value: string) => void;
}

export function ContactsOverviewAside({setSearchValue, handleJobTitleFilter}: Props) {
	const jobTitles = Object.keys(JobTitlesEnum);
	const {t} = useTranslation();

	return (
		<>
			<Box ml={1} mb={3} width={275} minWidth={180}>
				<ContactSearchBar setSearchValue={setSearchValue}/>
			</Box>
			<Box ml={4} mt={2}>
				<List dense>
					<Fragment key={1}>
						<Stack direction="row" alignItems="center" gap={1}>
							<LabelIcon/>
							<Typography variant="body1">{t('Contacts overview page.Job title')}</Typography>
						</Stack>
						{
							jobTitles.map((jobTitle: string) => (
								<ListItem sx={{marginLeft: 2}}>
									<Chip label={jobTitle}
										  onClick={() => handleJobTitleFilter(jobTitle)}/>
								</ListItem>
							))
						}
					</Fragment>
				</List>
			</Box>
		</>
	);
}