import * as React from 'react';
import {Dispatch, SetStateAction, SyntheticEvent, useEffect, useMemo, useState} from 'react';
import TextField from '@mui/material/TextField';
import Autocomplete from '@mui/material/Autocomplete';
import {fetchDataReactQuery} from "../../../utils/HttpRequestUtil";
import {IInstitution} from "../../../models/IInstitution";
import {debounce} from "lodash";
import {AutocompleteRenderInputParams, CircularProgress, InputAdornment} from "@mui/material";
import SearchIcon from "@mui/icons-material/Search";
import {useTranslation} from "react-i18next";
import {useQuery} from "@tanstack/react-query";
import {IContact} from "../../../models/IContact";
import ClearIcon from '@mui/icons-material/Clear';

interface Props {
	setValue: any | null;
	institution: IInstitution | null;
	setInstitution: Dispatch<SetStateAction<IInstitution | null>>;
	setContacts: Dispatch<SetStateAction<IContact[]>> | null;
}

export default function InstitutionSearchBar(props: Props) {
	const [searchValue, setSearchValue] = useState("");
	const [open, setOpen] = useState(false);
	const [options, setOptions] = useState<readonly IInstitution[]>([]);
	let loading = open && options.length === 0;
	const {t} = useTranslation();

	const {data: institutions} = useQuery<IInstitution[]>(
		['institution', searchValue],
		() => {
			if (searchValue !== "") {
				return fetchDataReactQuery(`/institutions/info/${searchValue}`);
			} else {
				return [];
			}
		}
	);

	useEffect(() => {
		if (!open) setOptions([]);
	}, [open]);

	useEffect(() => {
		if (institutions && institutions.length > 0) {
			setOptions([...institutions.map((i: IInstitution) => i)]);
			loading = false;
		}
	}, [institutions]);

	const handleSearchValueChange = (event: any) => setSearchValue(event.target.value);

	const debouncedSearchValueChangeHandler = useMemo(() => debounce(handleSearchValueChange, 300), []);

	const handleAutocompleteChange = (event: SyntheticEvent, value: any) => props.setInstitution(value);

	const handleCloseButtonClick = () => {
		setOpen(false);
		if (props.setContacts) props.setContacts?.([]);
		if (props.setValue) props.setValue?.('contacts', []);
	}

	const memoizedSearchValue = useMemo(() => {
		return props.institution;
	}, [props.institution]);

	return (
		<Autocomplete
			open={open}
			onOpen={() => setOpen(true)}
			onClose={() => setOpen(false)}
			clearIcon={<ClearIcon color='inherit' fontSize='small' onClick={handleCloseButtonClick}/>}
			onChange={(event: SyntheticEvent, value: any) => handleAutocompleteChange(event, value)}
			// show needed info from institution objects
			isOptionEqualToValue={(option, value) => {
				return `${option.name} - ${option.clientId}, ${option.city} ${option.zipCode}`
					=== `${value.name} - ${value.clientId}, ${value.city} ${value.zipCode}`
			}}
			value={memoizedSearchValue}
			getOptionLabel={(option) => `${option.name} - ${option.clientId}, ${option.city} ${option.zipCode}`}
			options={options}
			loading={loading}
			renderInput={(params: AutocompleteRenderInputParams) => (
				<TextField
					{...params}
					size="small"
					placeholder={t('Activities overview page.Search institution')}
					onKeyUp={debouncedSearchValueChangeHandler}
					InputProps={{
						...params.InputProps,
						startAdornment: (
							<InputAdornment position="start">
								<SearchIcon/>
							</InputAdornment>
						),
						endAdornment: (
							<>
								{loading && <CircularProgress color="inherit" size={20}/>}
								{params.InputProps.endAdornment}
							</>
						),
					}}
					InputLabelProps={{children: null}} // otherwise Textfield gives error because of params
				/>
			)}
		/>
	);
}

