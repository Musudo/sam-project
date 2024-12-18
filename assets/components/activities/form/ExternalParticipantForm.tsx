import React, {Dispatch, SetStateAction} from 'react';
import {IconButton, InputAdornment, TextField} from "@mui/material";
import EmailIcon from "@mui/icons-material/Email";
import ClearIcon from "@mui/icons-material/Clear";
import {useTranslation} from "react-i18next";

interface IExternal {
	index: number;
}

interface Props {
	external: IExternal;
	externals: IExternal[];
	register: any;
	unRegister: any;
	setExternals: Dispatch<SetStateAction<IExternal[]>>;
}

export function ExternalParticipantForm(props: Props) {
	const {t} = useTranslation();

	return (
		<TextField
			key={props.external.index}
			label={t('Activity form.External participant')}
			variant="outlined"
			fullWidth
			InputProps={{
				startAdornment: (
					<InputAdornment position="start">
						<EmailIcon color="primary"/>
					</InputAdornment>
				),
				endAdornment: (
					<InputAdornment position="end">
						<IconButton size="small"
									onClick={() => {
										props.setExternals(
											props.externals.filter((e: IExternal) => e.index !== props.external.index)
										);
										props.unRegister("external-" + props.external.index);
									}}>
							<ClearIcon/>
						</IconButton>
					</InputAdornment>
				)
			}}
			{...props.register(`external-${props.external.index}`)}
		/>
	);
}