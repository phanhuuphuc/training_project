import GroupRole from '@/Enums/GroupRole';

export const canCreate = (groupRole) => {
    return ([GroupRole.ADMIN].includes(groupRole))
};

export const canDelete = (groupRole) => {
    return ([GroupRole.ADMIN].includes(groupRole))
};

export const canEdit = (groupRole) => {
    return ([GroupRole.ADMIN, GroupRole.EDITOR].includes(groupRole))
};

export const canLock = (groupRole) => {
    return ([GroupRole.ADMIN, GroupRole.EDITOR].includes(groupRole))
};

export const canView = (groupRole) => {
    return ([GroupRole.ADMIN, GroupRole.EDITOR, , GroupRole.REVIEWER].includes(groupRole))
};

