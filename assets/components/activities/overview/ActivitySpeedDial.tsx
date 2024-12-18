import React, {useState} from 'react';
import {Box, ClickAwayListener, SpeedDial, SpeedDialAction, SpeedDialIcon, useMediaQuery} from '@mui/material';
import PeopleIcon from '@mui/icons-material/People';
import ConnectWithoutContactIcon from '@mui/icons-material/ConnectWithoutContact';
import LocalPhoneIcon from '@mui/icons-material/LocalPhone';
import {useNavigate} from 'react-router-dom';
import {useTranslation} from 'react-i18next';
import {SpeedDialDirectionsEnum} from '../../../enums/ComponentPropsEnums';
import {ActivityTypesEnum} from '../../../enums/ActivityTypesEnum';

interface Props {
	direction: SpeedDialDirectionsEnum | undefined;
}

const actions = [
	{icon: <PeopleIcon/>, name: ActivityTypesEnum.Physical},
	{icon: <ConnectWithoutContactIcon/>, name: ActivityTypesEnum.Online},
	{icon: <LocalPhoneIcon/>, name: ActivityTypesEnum.Phone},
];

export function ActivitySpeedDial({direction}: Props) {
	const [open, setOpen] = useState(false);
	const navigate = useNavigate();
	const {t} = useTranslation();
	const isMobile = useMediaQuery('(max-width: 600px)');

	const handleClick = () => setOpen(!open);

	const handleClickAway = () => setOpen(false);

	return (
		<Box sx={{transform: 'translateZ(0)', flexGrow: 1}}>
			<ClickAwayListener onClickAway={handleClickAway}>
				<SpeedDial
					ariaLabel='Activity speed dial'
					sx={{position: 'absolute', bottom: 6, right: 6}}
					icon={<SpeedDialIcon/>}
					direction={direction}
					onClick={handleClick}
					open={open}
				>
					{actions.map((action) => (
						<SpeedDialAction
							key={action.name}
							icon={action.icon}
							tooltipTitle={!isMobile ? t(`Common.Activity speed dial.${action.name}`) : ''}
							onClick={() => navigate(`/activities/new/${action.name}`)}
						/>
					))}
				</SpeedDial>
			</ClickAwayListener>
		</Box>
	);
}