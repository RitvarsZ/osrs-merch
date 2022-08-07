export const FormatNumber = (number: number): string => {
  return new Intl.NumberFormat('en-US').format(number);
}
