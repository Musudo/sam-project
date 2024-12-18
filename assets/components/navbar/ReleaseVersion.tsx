import {Badge, Stack} from "@mui/material";
import Typography from "@mui/material/Typography";
import * as React from "react";
import {NavbarTypesEnum} from "../../enums/ComponentPropsEnums";

interface Props {
	type: NavbarTypesEnum;
}

export default function ReleaseVersion({type}: Props) {

	return (
		<Stack direction="row" alignItems="center">
			<Typography variant="subtitle1" color={type === NavbarTypesEnum.Main ? "#e3f2fd" : ""}>
				<em>Release 1.0 beta 5</em>
			</Typography>
			<Badge badgeContent="New"
				   color="secondary"
				   sx={{ml: 3}}/>
		</Stack>
	);
}