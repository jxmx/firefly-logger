# Copyright (c) 2024, Jason McCormick 
# Some code originally Copyright (c) 2024, Christian Kuhtz
# under the 3-Clause BSD license. 
# Redistribution and use in source and binary forms, with or without
# # modification, are permitted provided that the following conditions are met:
# 
# 1. Redistributions of source code must retain the above copyright notice, this
#    list of conditions and the following disclaimer.
# 
# 2. Redistributions in binary form must reproduce the above copyright notice,
#    this list of conditions and the following disclaimer in the documentation
#    and/or other materials provided with the distribution.
# 
# 3. Neither the name of the copyright holder nor the names of its
#    contributors may be used to endorse or promote products derived from
#    this software without specific prior written permission.
# 
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
# AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
# DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
# SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
# CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
# OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

import convertdate
import datetime
import logging
from PyQt5.QtCore import QByteArray, QDataStream, QIODevice
from . import wsjt_qso

__BUILD_ID = "@@HEAD-DEVELOP@@"
log = logging.getLogger(__name__)

# decode the UTF-8 strings embedded in the QDatastream of WSJT-X UDP messages
def decode_utf8_str(stream):
    len = stream.readUInt32()
    if len == 0xffffffff: # null string is mashed into an empty string
        return ""
    else:
        bytes = stream.readRawData(len)
        return bytes.decode("utf-8)")

# decode qdatetime into an ISO 8601 timestamp string
def decode_qdatetime(stream):
    julian_days = stream.readInt64() # QDate
    msecs_since_midnight = stream.readUInt32()
    timespec = stream.readUInt8()
    if timespec == 3:
        raise wsjtDecoderException("non-spec qso logged message received with timespec 3 (timezones).")
    if timespec == 2:
        offset = stream.readInt32()
        log.debug("offset: %s", offset)
        raise wsjtDecoderException("unable to deal with QDateTime objects with timespec=2 (offset={}).".format(offset))
            
    gregorian_datetime = convertdate.julianday.to_datetime(julian_days)

    # Apparently this is always noon. since the QTime is milliseconds since midnight, if we add it to the 
    # gregorgian_date the resulting timestamp will be off by 12 hours every time.
    # Instead, we recreate the date with starting point at midnight and add milliseconds to it.  This is safe
    # because julian_days will always be an integer and never fractions of a day.
    combined_datetime = datetime.datetime(
        gregorian_datetime.year, gregorian_datetime.month,
        gregorian_datetime.day,0,0) + datetime.timedelta(milliseconds=msecs_since_midnight)
    dt = combined_datetime.strftime('%Y-%m-%d %H:%M:%S')

    #log.debug("julian_days: %s", julian_days)
    #log.debug("msecs_since_midnight: %s", msecs_since_midnight)
    #log.debug("timespec: %s", timespec)
    #log.debug("gregorian_datetime: %s", gregorian_datetime)
    #log.debug("combined_datetime: %s", combined_datetime)
    #log.debug("combined_datetime.isoformat()Z:  %s", iso8601_datetime)
    #log.debug("iso8601_datetime:  %s", iso8601_datetime)
    
    return dt

# dict of WSJT-X message type codes
wsjt_msg_type = {
    0 : "Heartbeat",
    1 : "Status Update",
    2 : "Message Decode",
    3 : "Clear Decodes",
    4 : "Control Message to WSJT-X",
    5 : "QSO Logged",
    6 : "WSJT-X Shutdown",
    7 : "Replay Previous Decodes",
    8 : "Halt Transmissions",
    9 : "Send Free Text Message",
    10 : "WSPR Decode",
    11 : "Location Update",
    12 : "Logged ADIF",
    13 : "Callsign Highlight",
    14 : "Switch WSJT-X Configuration",
    15 : "Configure WSJT-X"
}

# taken in a stream of bytes
def decode_message(data: bytes, source_addr: str):
    
    #log.debug("source: %s", source_addr)
    
    buffer = QByteArray(data)
    stream = QDataStream(buffer)
    stream.setByteOrder(QDataStream.BigEndian)

    # check magic number match
    magic = stream.readUInt32()
    if magic != 0xadbccbda:
        raise wsjtMessageDecodeError(f"bad magic number ({magic})")
        
    # check schema number match
    schema = stream.readUInt32()
    if schema != 2:
        raise wsjtMessageDecodeError(f"unsupported schema ({schema})")

    # if we got here, we know we have something that looks like the message we're expecting from WSJT-X
    message_type = stream.readUInt32()
    id = decode_utf8_str(stream)
    #log.debug("id: %s", id)
    #log.debug("message_type: %d ", message_type)
      
    match message_type:
         
        case 5: # QSO logged
            qso = wsjt_qso.WsjtQso();
            qso.datetime_off = decode_qdatetime(stream)
            qso.dx_call = decode_utf8_str(stream)
            qso.dx_grid = decode_utf8_str(stream)
            qso.tx_freq = stream.readUInt64()
            qso.mode = decode_utf8_str(stream)
            qso.report_sent = decode_utf8_str(stream)
            qso.report_rcvd = decode_utf8_str(stream)
            qso.tx_power = decode_utf8_str(stream)
            qso.comments = decode_utf8_str(stream)
            qso.name = decode_utf8_str(stream)
            qso.datetime_on = decode_qdatetime(stream)
            qso.op_call = decode_utf8_str(stream)
            qso.de_call = decode_utf8_str(stream)
            qso.de_grid = decode_utf8_str(stream)
            qso.exch_sent = decode_utf8_str(stream)
            qso.exch_rcvd = decode_utf8_str(stream)
            qso.adif_prop_mode = decode_utf8_str(stream)

        case _:
            #log.debug("ignored message type %d (%s)", 
            #          message_type, wsjt_msg_type[message_type])
            qso = None

    return qso

class wsjtDecoderException(Exception): 
    """ specific exception for general functions in wsjt2ffdl.wsjt_decoder """

class wsjtMessageDecodeError(Exception):
    """ very-specific exception on message decode failures for valid/unsupported messages """