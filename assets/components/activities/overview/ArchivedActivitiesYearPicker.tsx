import {LocalizationProvider} from "@mui/x-date-pickers/LocalizationProvider";
import {AdapterDateFns} from "@mui/x-date-pickers/AdapterDateFns";
import {DatePicker} from "@mui/x-date-pickers";
import {FormControl, TextField} from "@mui/material";
import React from "react";

interface Props {
	archivedYear: Date | null;
	handleArchivedYearChange: (newValue: Date | null) => void;
}

export function ArchivedActivitiesYearPicker({archivedYear, handleArchivedYearChange}: Props) {

	return (
		<FormControl sx={{maxWidth: 210}}>
			<LocalizationProvider dateAdapter={AdapterDateFns}>
				<DatePicker
					views={["year"]}
					label="Select year"
					value={archivedYear}
					onChange={handleArchivedYearChange}
					openTo="year"
					minDate={new Date("2022-01-01")}
					maxDate={new Date()}
					renderInput={(params) => <TextField {...params} />}
				/>
			</LocalizationProvider>
		</FormControl>
	);
}